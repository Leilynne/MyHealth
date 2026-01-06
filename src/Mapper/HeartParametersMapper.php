<?php

declare(strict_types=1);

namespace App\Mapper;

use App\ApiResource\HeartParametersResource;
use App\Entity\HeartParameters;

readonly class HeartParametersMapper
{
    public function mapEntityToResource(HeartParameters $heartParameters): HeartParametersResource
    {
        $resource = new HeartParametersResource();
        $resource->arm = $heartParameters->getArm()->value;
        $resource->heartbeat = $heartParameters->getHeartBeat();
        $resource->systola = $heartParameters->getSystola();
        $resource->diastola = $heartParameters->getDiastola();
        $resource->datetime = $heartParameters->getDatetime()->format('Y-m-d H:i:s');

        return $resource;
    }
}
