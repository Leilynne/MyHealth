<?php

declare(strict_types=1);

namespace App\Mapper;

use App\ApiResource\ExerciseResource;
use App\Entity\Exercise;

readonly class ExerciseMapper
{
    public function mapEntityToResource(Exercise $exercise): ExerciseResource
    {
        $resource = new ExerciseResource();
        $resource->id = $exercise->getId();
        $resource->name = $exercise->getName();
        $resource->description = $exercise->getDescription();
        $resource->kcalPerHour = $exercise->getKcalPerHour();
        $resource->isSystem = $exercise->isSystem();

        return $resource;
    }
}
