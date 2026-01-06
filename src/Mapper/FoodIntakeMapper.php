<?php

declare(strict_types=1);

namespace App\Mapper;

use App\ApiResource\FoodIntakeResource;
use App\Entity\FoodIntake;

readonly class FoodIntakeMapper
{
    public function mapEntityToResource(FoodIntake $foodIntake): FoodIntakeResource
    {
        $resource = new FoodIntakeResource();
        $resource->id = $foodIntake->getId();
        $resource->amount = $foodIntake->getAmount();
        $resource->date = $foodIntake->getDate()->format('Y-m-d');
        $resource->type = $foodIntake->getType()->value;
        $resource->foodId = $foodIntake->getFood()->getId();
        $resource->foodName = $foodIntake->getFood()->getName();

        return $resource;
    }
}
