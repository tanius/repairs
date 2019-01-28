<?php
/**
Copyright 2014-2017 Nick Korbel

This file is part of Booked Scheduler.

Booked Scheduler is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Booked Scheduler is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Booked Scheduler.  If not, see <http://www.gnu.org/licenses/>.
 */

class CalendarFilters
{
	const FilterSchedule = 'schedule';
	const FilterResource = 'resource';

	/**
	 * @var array|CalendarFilter[]
	 */
	private $filters = array();

	/**
	 * @var ResourceGroupTree
	 */
	private $resourceGroupTree;

	/**
	 * @param array|Schedule[] $schedules
	 * @param array|ResourceDto[] $resources
	 * @param int $selectedScheduleId
	 * @param int $selectedResourceId
	 * @param ResourceGroupTree $resourceGroupTree
	 */
	public function __construct($schedules, $resources, $selectedScheduleId, $selectedResourceId, ResourceGroupTree $resourceGroupTree)
	{
		$this->resourceGroupTree = $resourceGroupTree;
        
		if (!empty($resources))
		{
			$this->filters[] = new CalendarFilter(self::FilterSchedule, null, Resources::GetInstance()->GetString("AllReservations"), (empty($selectedResourceId) && empty($selectedScheduleId)));
		}
		foreach ($schedules as $schedule)
		{
			if ($this->ScheduleContainsNoResources($schedule, $resources))
			{
				continue;
			}

			$filter = new CalendarFilter(self::FilterSchedule, $schedule->GetId(), $schedule->GetName(), (empty($selectedResourceId) && $selectedScheduleId == $schedule->GetId()));

			foreach ($resources as $resource)
			{
				if ($resource->GetScheduleId() == $schedule->GetId())
				{
					$filter->AddSubFilter(new CalendarFilter(self::FilterResource, $resource->GetResourceId(), $resource->GetName(), ($selectedResourceId == $resource->GetResourceId())));
				}
			}

			$this->filters[] = $filter;
		}
	}

	/**
	 * @return bool
	 */
	public function IsEmpty()
	{
		return empty($this->filters);
	}

	/**
	 * @return array|CalendarFilter[]
	 */
	public function GetFilters()
	{
		return $this->filters;
	}

	/**
	 * @return ResourceGroupTree
	 */
	public function GetResourceGroupTree()
	{
		return $this->resourceGroupTree;
	}

	/**
	 * @param Schedule $schedule
	 * @param ResourceDto[] $resources
	 * @return bool
	 */
	private function ScheduleContainsNoResources(Schedule $schedule, $resources)
	{
		foreach ($resources as $resource)
		{
			if ($resource->GetScheduleId() == $schedule->GetId())
			{
				return false;
			}
		}

		return true;
	}
}

class CalendarFilter
{
	/**
	 * @var array|CalendarFilter[]
	 */
	private $filters = array();

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var bool
	 */
	private $selected;

	/**
	 * @return string
	 */
	public function Name()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function Id()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function Type()
	{
		return $this->type;
	}

	/**
	 * @return bool
	 */
	public function Selected()
	{
		return $this->selected;
	}

	/**
	 * Determines if any subfilter of this filter is marked as the currently active one 
	 * in the database (i.e. "selected").
	 * 
	 * @return bool
	 */
	public function SubfilterSelected()
	{
		foreach ($this->filters as $subfilter) {
			if ($subfilter->Selected())
				return true;
		}
		return false;
	}

	public function __construct($type, $id, $name, $selected)
	{
		$this->type = $type;
		$this->id = $id;
		$this->name = $name;
		$this->selected = $selected;
	}

	public function AddSubFilter(CalendarFilter $subfilter)
	{
		$this->filters[] = $subfilter;
	}

	/**
	 * @return array|CalendarFilter[]
	 */
	public function GetFilters()
	{
		return $this->filters;
	}

}
