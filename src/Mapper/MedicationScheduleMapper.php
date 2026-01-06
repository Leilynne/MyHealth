<?php

declare(strict_types=1);

namespace App\Mapper;

use App\ApiResource\MedicationScheduleResource;
use App\Entity\MedicationSchedule;

readonly class MedicationScheduleMapper
{
    public function mapEntityToResource(MedicationSchedule $medicationSchedule): MedicationScheduleResource
    {
        $resource = new MedicationScheduleResource();
        $resource->medicationScheduleId = $medicationSchedule->getId();
        $resource->timeOfDay = $medicationSchedule->getTimeOfDay()->value;
        $resource->medicineId = $medicationSchedule->getMedicine()->getId();
        $resource->medicineName = $medicationSchedule->getMedicine()->getName();

        return $resource;
    }
}
