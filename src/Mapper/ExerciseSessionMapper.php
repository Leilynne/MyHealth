<?php

declare(strict_types=1);

namespace App\Mapper;

use App\ApiResource\ExerciseSessionResource;
use App\Entity\ExerciseSession;

readonly class ExerciseSessionMapper
{
    public function mapEntityToResource(ExerciseSession $exerciseSession): ExerciseSessionResource
    {
        $resource = new ExerciseSessionResource();
        $resource->duration = $exerciseSession->getDuration();
        $resource->exerciseId = $exerciseSession->getExercise()->getId();
        $resource->totalKcal = (int) $exerciseSession->getTotalKcal();
        $resource->exerciseName = $exerciseSession->getExercise()->getName();
        $resource->performedAt = $exerciseSession->getPerformedAt()->format('Y-m-d H:i:s');

        return $resource;
    }
}
