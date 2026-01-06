<?php

declare(strict_types=1);

namespace App\Mapper;

use App\ApiResource\MedicineResource;
use App\Entity\Medicine;

readonly class MedicineMapper
{
    public function mapEntityToResource(Medicine $medicine): MedicineResource
    {
        $resource = new MedicineResource();
        $resource->id = $medicine->getId();
        $resource->dose = $medicine->getDose();
        $resource->name = $medicine->getName();
        $resource->description = $medicine->getDescription();

        return $resource;
    }
}
