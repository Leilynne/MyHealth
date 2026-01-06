<?php

declare(strict_types=1);

namespace App\Mapper;

use App\ApiResource\WeightParametersResource;
use App\Entity\WeightParameters;

readonly class WeightParametersMapper
{
    public function mapEntityToResource(WeightParameters $weightParameters): WeightParametersResource
    {
        $resource = new WeightParametersResource();
        $resource->weight = $weightParameters->getWeight();
        $resource->datetime = $weightParameters->getDatetime()->format('Y-m-d H:i:s');

        return $resource;
    }
}
