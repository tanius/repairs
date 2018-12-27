<?php
/**
 * Copyright 2017 Nick Korbel
 *
 * This file is part of Booked Scheduler.
 *
 * Booked Scheduler is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Booked Scheduler is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Booked Scheduler.  If not, see <http://www.gnu.org/licenses/>.
 */

class AccessoryAggregation
{
	private $quantities = array();

	/**
	 * @var \DateRange
	 */
	private $duration;

	/**
	 * @var string[]
	 */
	private $addedReservations = array();

	/**
	 * @param array|AccessoryToCheck[] $accessories
	 * @param DateRange $duration
	 */
	public function __construct($accessories, $duration)
	{
		foreach ($accessories as $a)
		{
			/** @var Date $date */
			foreach ($duration->Dates() as $date)
			{
				$this->quantities[$a->GetId()][$date->GetDate()->Timestamp()] = 0;
			}
		}

		$this->duration = $duration;

	}

	/**
	 * @param AccessoryReservation $accessoryReservation
	 */
	public function Add(AccessoryReservation $accessoryReservation)
	{
		if ($accessoryReservation->GetStartDate()->Equals($this->duration->GetEnd()) || $accessoryReservation->GetEndDate()->Equals($this->duration->GetBegin()))
		{
			return;
		}

		$accessoryId = $accessoryReservation->GetAccessoryId();

		$key = $accessoryReservation->GetReferenceNumber() . $accessoryId;

		if (array_key_exists($key, $this->addedReservations))
		{
			return;
		}

		$this->addedReservations[$key] = true;

		if (array_key_exists($accessoryId, $this->quantities))
		{
			/** @var Date $date */
			foreach ($accessoryReservation->GetDuration()->Dates() as $date)
			{
				$dateKey = $date->GetDate()->Timestamp();
				if (!array_key_exists($dateKey, $this->quantities[$accessoryId]))
				{
					$this->quantities[$accessoryId][$dateKey] = 0;
				}
				$this->quantities[$accessoryId][$dateKey] += $accessoryReservation->QuantityReserved();
			}
		}
	}

	/**
	 * @param int $accessoryId
	 * @return int
	 */
	public function GetQuantity($accessoryId)
	{
		// Added by matthias@ansorgs.de 2018-12-26
		return $this->GetConsumption($accessoryId);

		// Original implementation, disabled by matthias@ansorgs.de 2018-12-26
/*
		$quantity = 0;

		foreach ($this->quantities[$accessoryId] as $date => $q)
		{
			if ($q > $quantity)
			{
				$quantity = $q;
			}
		}
		return $quantity;
*/
	}

	// Added by matthias@ansorgs.de 2018-12-26.
	public function GetConsumption($accessoryId)
	{
		$getConsumptionCommand = new GetAccessoryConsumptionCommand($accessoryId);

                $result = ServiceLocator::GetDatabase()->Query($getConsumptionCommand);

                $consumption = 0;

                $row = $result->GetRow();
		if (!empty($row['consumption'])) {
			$consumption = $row['consumption'];
		}
                $result->Free();

                return $consumption;
	}

}
