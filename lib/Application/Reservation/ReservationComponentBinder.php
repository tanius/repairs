<?php
/**
Copyright 2012-2017 Nick Korbel

This file is part of Booked Scheduler is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Booked Scheduler.  If not, see <http://www.gnu.org/licenses/>.
 */

interface IReservationComponentBinder
{
	public function Bind(IReservationComponentInitializer $initializer);
}

class ReservationDateBinder implements IReservationComponentBinder
{
	/**
	 * @var IScheduleRepository
	 */
	private $scheduleRepository;

	public function __construct(IScheduleRepository $scheduleRepository)
	{
		$this->scheduleRepository = $scheduleRepository;
	}

	public function Bind(IReservationComponentInitializer $initializer)
	{
		$timezone = $initializer->GetTimezone();
		$reservationDate = $initializer->GetReservationDate();
		$requestedEndDate = $initializer->GetEndDate();
		$requestedStartDate = $initializer->GetStartDate();
		$requestedScheduleId = $initializer->GetScheduleId();

		$requestedDate = ($reservationDate == null) ? Date::Now()->ToTimezone($timezone) : $reservationDate->ToTimezone($timezone);

		$startDate = ($requestedStartDate == null) ? $requestedDate : $requestedStartDate->ToTimezone($timezone);
		$endDate = ($requestedEndDate == null) ? $requestedDate : $requestedEndDate->ToTimezone($timezone);

        if ($initializer->IsNew())
		{
			$resource = $initializer->PrimaryResource();

			if ($resource->GetMinimumLength() != null && !$resource->GetMinimumLength()->Interval()->IsNull() &&
                !DateDiff::BetweenDates($startDate, $endDate)->GreaterThan($resource->GetMinimumLength()->Interval()))
			{
				$endDate = $startDate->ApplyDifference($resource->GetMinimumLength()->Interval());
			}
		}

		$layout = $this->scheduleRepository->GetLayout($requestedScheduleId, new ReservationLayoutFactory($timezone));

        $startPeriods = $this->GetStartPeriods($layout, $startDate);
        $endPeriods = $this->GetEndPeriods($layout, $startDate, $endDate);

		$initializer->SetDates($startDate, $endDate, $startPeriods, $endPeriods);

		$hideRecurrence = !$initializer->CurrentUser()->IsAdmin && Configuration::Instance()->GetSectionKey(ConfigSection::RESERVATION,
																											ConfigKeys::RESERVATION_PREVENT_RECURRENCE,
																											new BooleanConverter());
		$initializer->HideRecurrence($hideRecurrence);
	}

    /**
     * @param IScheduleLayout $layout
     * @param Date $startDate
     * @return SchedulePeriod[]
     */
    protected function GetStartPeriods($layout, &$startDate)
    {
        $startPeriods = $layout->GetLayout($startDate, true);

        return $this->GetPeriods($startPeriods, $layout, $startDate);

    }

	/**
	 * @param IScheduleLayout $layout
	 * @param Date $startDate
	 * @param Date $endDate
	 * @return SchedulePeriod[]
	 */
	protected function GetEndPeriods($layout, $startDate, &$endDate)
	{
		$endPeriods = $layout->GetLayout($endDate, true);
		if (count($endPeriods) == 0 && $startDate->AddDays(1)->Equals($endDate))
		{
			// no periods on the next day, return midnight to let the reservation end at the top of the hour
			return array(new SchedulePeriod($endDate->SetTimeString('00:00'), $endDate->SetTimeString('00:00', true)));
		}
		return $this->GetPeriods($endPeriods, $layout, $endDate);
	}

	/**
	 * @param SchedulePeriod[] $startPeriods
	 * @param IScheduleLayout $layout
	 * @param Date $startDate
	 * @param int $iteration
	 * @return array|SchedulePeriod[]
	 */
	private function GetPeriods($startPeriods, $layout, &$startDate, $iteration = 0)
	{
		if (count($startPeriods) > 1 && $startPeriods[0]->Begin()->Compare($startPeriods[1]->Begin()) > 0) {
			$period = array_shift($startPeriods);
			$startPeriods[] = $period;
		}

		if (count($startPeriods) == 0 && $iteration < 7)
		{
			$startDate = $startDate->AddDays(1);
			return $this->GetPeriods($layout->GetLayout($startDate, true), $layout, $startDate, ++$iteration);
		}
		return $startPeriods;
	}
}

class ReservationUserBinder implements IReservationComponentBinder
{
	/**
	 * @var IUserRepository
	 */
	private $userRepository;

	/**
	 * @var IReservationAuthorization
	 */
	private $reservationAuthorization;

	/**
	 * @param IUserRepository $userRepository
	 * @param IReservationAuthorization $reservationAuthorization
	 */
	public function __construct($userRepository, $reservationAuthorization)
	{
		$this->userRepository = $userRepository;
		$this->reservationAuthorization = $reservationAuthorization;
	}

	public function Bind(IReservationComponentInitializer $initializer)
	{
		$userId = $initializer->GetOwnerId();
		$currentUser = $initializer->CurrentUser();
		$canChangeUser = $this->reservationAuthorization->CanChangeUsers($currentUser);

		$initializer->SetCanChangeUser($canChangeUser);

		$reservationUser = $this->userRepository->GetById($userId);
		$initializer->SetReservationUser($reservationUser);

		$hideUser = Configuration::Instance()->GetSectionKey(ConfigSection::PRIVACY,
															 ConfigKeys::PRIVACY_HIDE_USER_DETAILS,
															 new BooleanConverter());

		$initializer->ShowUserDetails(!$hideUser || $currentUser->IsAdmin || $userId == $currentUser->UserId);
		$initializer->ShowReservationDetails(true);
		$initializer->SetShowParticipation(!$hideUser || $currentUser->IsAdmin || $currentUser->IsGroupAdmin);
	}
}

class ReservationResourceBinder implements IReservationComponentBinder
{
	/**
	 * @var IResourceService
	 */
	private $resourceService;

	public function __construct(IResourceService $resourceService)
	{
		$this->resourceService = $resourceService;
	}

	public function Bind(IReservationComponentInitializer $initializer)
	{
		$requestedScheduleId = $initializer->GetScheduleId();
		$requestedResourceId = $initializer->GetResourceId();
		$groups = $this->resourceService->GetResourceGroups($requestedScheduleId, $initializer->CurrentUser());

		$resources = $groups->GetAllResources();
		if (empty($requestedResourceId) && count($resources) > 0)
		{
			$first = reset($resources);
			$requestedResourceId = $first->Id;
		}

		$bindableResourceData = $this->GetBindableResourceData($resources, $requestedResourceId);

		if ($bindableResourceData->NumberAccessible <= 0)
		{
			$initializer->RedirectToError(ErrorMessages::INSUFFICIENT_PERMISSIONS);
			return;
		}

		$initializer->BindResourceGroups($groups);
		$initializer->BindAvailableResources($resources);
		$accessories = $this->resourceService->GetAccessories();
		$initializer->BindAvailableAccessories($accessories);
		$initializer->ShowAdditionalResources($bindableResourceData->NumberAccessible > 0);
		$initializer->SetReservationResource($bindableResourceData->ReservationResource);
	}

	/**
	 * @param $resources array|ResourceDto[]
	 * @param $requestedResourceId int
	 * @return BindableResourceData
	 */
	private function GetBindableResourceData($resources, $requestedResourceId)
	{
		$bindableResourceData = new BindableResourceData();

		if (count($resources) > 0)
        {
            $bindableResourceData->SetReservationResource($resources[0]);
        }

		/** @var $resource ResourceDto */
		foreach ($resources as $resource)
		{
			$bindableResourceData->AddAvailableResource($resource);
			if ($resource->Id == $requestedResourceId)
			{
				$bindableResourceData->SetReservationResource($resource);
			}
		}

		return $bindableResourceData;
	}
}

class ReservationDetailsBinder implements IReservationComponentBinder
{
	/**
	 * @var IReservationAuthorization
	 */
	private $reservationAuthorization;

	/**
	 * @var IExistingReservationPage
	 */
	private $page;

	/**
	 * @var ReservationView
	 */
	private $reservationView;

	/**
	 * @var IPrivacyFilter
	 */
	private $privacyFilter;

	public function __construct(IReservationAuthorization $reservationAuthorization, IExistingReservationPage $page,
								ReservationView $reservationView, IPrivacyFilter $privacyFilter)
	{
		$this->reservationAuthorization = $reservationAuthorization;
		$this->page = $page;
		$this->reservationView = $reservationView;
		$this->privacyFilter = $privacyFilter;
	}

	public function Bind(IReservationComponentInitializer $initializer)
	{
		$this->page->SetAdditionalResources($this->reservationView->AdditionalResourceIds);
		$this->page->SetTitle($this->reservationView->Title);
		$this->page->SetDescription($this->reservationView->Description);
		$this->page->SetReferenceNumber($this->reservationView->ReferenceNumber);
		$this->page->SetReservationId($this->reservationView->ReservationId);
		$this->page->SetSeriesId($this->reservationView->SeriesId);

		$this->page->SetIsRecurring($this->reservationView->IsRecurring());
		$this->page->SetRepeatType($this->reservationView->RepeatType);
		$this->page->SetRepeatInterval($this->reservationView->RepeatInterval);
		$this->page->SetRepeatMonthlyType($this->reservationView->RepeatMonthlyType);

		if ($this->reservationView->RepeatTerminationDate != null)
		{
			$this->page->SetRepeatTerminationDate($this->reservationView->RepeatTerminationDate->ToTimezone($initializer->GetTimezone()));
		}
		$this->page->SetRepeatWeekdays($this->reservationView->RepeatWeekdays);


		$participants = $this->reservationView->Participants;
		$invitees = $this->reservationView->Invitees;

		$this->page->SetParticipants($participants);
		$this->page->SetInvitees($invitees);

		$this->page->SetParticipatingGuests($this->reservationView->ParticipatingGuests);
		$this->page->SetInvitedGuests($this->reservationView->InvitedGuests);

		$this->page->SetAllowParticipantsToJoin($this->reservationView->AllowParticipation);

		$this->page->SetAccessories($this->reservationView->Accessories);

		$currentUser = $initializer->CurrentUser();

		$this->page->SetCurrentUserParticipating($this->IsCurrentUserParticipating($currentUser->UserId));
		$this->page->SetCurrentUserInvited($this->IsCurrentUserInvited($currentUser->UserId));
		$this->page->SetCanAlterParticipation($this->reservationView->EndDate->GreaterThan(Date::Now()));

		$canBeEdited = $this->reservationAuthorization->CanEdit($this->reservationView, $currentUser);
		$this->page->SetIsEditable($canBeEdited);
		$this->page->SetIsApprovable($this->reservationAuthorization->CanApprove($this->reservationView, $currentUser));
		$this->page->SetRequiresApproval($this->reservationView->RequiresApproval());

		$this->page->SetAttachments($this->reservationView->Attachments);

		$showUser = $this->privacyFilter->CanViewUser($initializer->CurrentUser(), $this->reservationView);
#		$showUser = true;
		$showDetails = $this->privacyFilter->CanViewDetails($initializer->CurrentUser(), $this->reservationView);
#		$showDetails = true;

		$initializer->ShowUserDetails($showUser);
		$initializer->ShowReservationDetails($showDetails);

		if (!empty($this->reservationView->StartReminder))
		{
			$this->page->SetStartReminder($this->reservationView->StartReminder->GetValue(),
										  $this->reservationView->StartReminder->GetInterval());
		}
		if (!empty($this->reservationView->EndReminder))
		{
			$this->page->SetEndReminder($this->reservationView->EndReminder->GetValue(),
										$this->reservationView->EndReminder->GetInterval());
		}

		$this->page->SetCheckInRequired(false);
		$this->page->SetCheckOutRequired(false);
		$this->page->SetAutoReleaseMinutes(null);
		$this->SetCheckinRequired();
		$this->SetCheckoutRequired();
		$this->SetAutoReleaseMinutes();
	}

	private function IsCurrentUserParticipating($currentUserId)
	{
		/** @var $user ReservationUserView */
		foreach ($this->reservationView->Participants as $user)
		{
			if ($user->UserId == $currentUserId)
			{
				return true;
			}
		}
		return false;
	}

	private function IsCurrentUserInvited($currentUserId)
	{
		/** @var $user ReservationUserView */
		foreach ($this->reservationView->Invitees as $user)
		{
			if ($user->UserId == $currentUserId)
			{
				return true;
			}
		}
		return false;
	}

	private function SetCheckinRequired()
	{
	    $this->page->SetCheckInRequired($this->reservationView->IsCheckinAvailable());
	}

	private function SetCheckoutRequired()
	{
	    $this->page->SetCheckOutRequired($this->reservationView->IsCheckoutAvailable());
	}

	private function SetAutoReleaseMinutes()
	{
		$minAutoReleaseMinutes = null;
		if ($this->reservationView->CheckinDate->ToString() == '')
		{
			$minAutoReleaseMinutes = $this->reservationView->AutoReleaseMinutes();
		}
		$this->page->SetAutoReleaseMinutes($minAutoReleaseMinutes);
	}
}
