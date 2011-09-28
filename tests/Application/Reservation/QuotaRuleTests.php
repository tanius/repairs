<?php
require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'tests/Domain/Reservation/TestReservationSeries.php');

class QuotaRuleTests extends TestBase
{
	/**
	 * @var IReservationViewRepository|PHPUnit_Framework_MockObject_MockObject
	 */
	public $reservationViewRepository;

	/**
	 * @var IQuotaRepository|PHPUnit_Framework_MockObject_MockObject
	 */
	public $quotaRepository;

	/**
	 * @var IUserRepository|PHPUnit_Framework_MockObject_MockObject
	 */
	public $userRepository;

	/**
	 * @var IScheduleRepository|PHPUnit_Framework_MockObject_MockObject
	 */
	public $scheduleRepository;

	public function setup()
	{
		parent::setup();
		
		$this->reservationViewRepository = $this->getMock('IReservationViewRepository');
		$this->quotaRepository = $this->getMock('IQuotaRepository');
		$this->userRepository = $this->getMock('IUserRepository');
		$this->scheduleRepository = $this->getMock('IScheduleRepository');
	}

	public function teardown()
	{
		parent::teardown();
	}

	public function testWhenQuotaThatAppliesToReservationResourceAndUserGroupIsNotExceed()
	{
		$scheduleId = 971243;
		$timezone = 'America/New_York';
		
		$userId = 10;
		$groupId1 = 8287;
		$groupId2 = 102;
		
		$user = new FakeUser();
		$user->SetGroups(array($groupId1, $groupId2));

		$schedule = new Schedule(1, null, null, null, null, $timezone);
		$series = ReservationSeries::Create($userId, new FakeBookableResource(20), $scheduleId, null, null, new TestDateRange(), new RepeatNone(), new FakeUserSession());
		$series->AddResource(new FakeBookableResource(22));

		$quota1 = $this->GetMock('IQuota');
		$quota2 = $this->GetMock('IQuota');
		$quota3 = $this->GetMock('IQuota');
		
		$quotas = array($quota1, $quota2, $quota3);

		$this->quotaRepository->expects($this->once())
			->method('LoadAll')
			->will($this->returnValue($quotas));

		$this->userRepository->expects($this->once())
			->method('LoadById')
			->with($this->equalTo($userId))
			->will($this->returnValue($user));

		$this->scheduleRepository->expects($this->once())
			->method('LoadById')
			->with($this->equalTo($scheduleId))
			->will($this->returnValue($schedule));

		$this->ChecksAgainstQuota($quota1, $series, $this->reservationViewRepository, $schedule, $user);
		$this->ChecksAgainstQuota($quota2, $series, $this->reservationViewRepository, $schedule, $user);
		$this->ChecksAgainstQuota($quota3, $series, $this->reservationViewRepository, $schedule, $user);

		$rule = new QuotaRule($this->quotaRepository, $this->reservationViewRepository, $this->userRepository, $this->scheduleRepository);
		$result = $rule->Validate($series);

		$this->assertTrue($result->IsValid(), 'no quotas were exceeded');
		
	}

	public function testFirstQuotaExceeded()
	{
		$scheduleId = 971243;
		$timezone = 'America/New_York';

		$userId = 10;
		$groupId1 = 8287;
		$groupId2 = 102;

		$user = new FakeUser();
		$user->SetGroups(array($groupId1, $groupId2));

		$schedule = new Schedule(1, null, null, null, null, $timezone);
		$series = ReservationSeries::Create($userId, new FakeBookableResource(20), $scheduleId, null, null, new TestDateRange(), new RepeatNone(), new FakeUserSession());
		$series->AddResource(new FakeBookableResource(22));

		$quota1 = $this->GetMock('IQuota');
		$quota2 = $this->GetMock('IQuota');

		$quotas = array($quota1, $quota2);

		$this->quotaRepository->expects($this->once())
			->method('LoadAll')
			->will($this->returnValue($quotas));

		$this->userRepository->expects($this->once())
			->method('LoadById')
			->with($this->equalTo($userId))
			->will($this->returnValue($user));

		$this->scheduleRepository->expects($this->once())
			->method('LoadById')
			->with($this->equalTo($scheduleId))
			->will($this->returnValue($schedule));

		$this->ChecksAgainstQuota($quota1, $series, $this->reservationViewRepository, $schedule, $user, true);

		$quota2->expects($this->never())
			->method('ExceedsQuota');

		$rule = new QuotaRule($this->quotaRepository, $this->reservationViewRepository, $this->userRepository, $this->scheduleRepository);
		$result = $rule->Validate($series);

		$this->assertFalse($result->IsValid(), 'first quotas was exceeded');
	}


	private function ChecksAgainstQuota($quota, $series, $repo, $schedule, $user, $exceeds = false)
	{
		$quota->expects($this->once())
			->method('ExceedsQuota')
			->with($this->equalTo($series), $this->equalTo($user), $this->equalTo($schedule), $this->equalTo($repo))
			->will($this->returnValue($exceeds));
	}
}
?>