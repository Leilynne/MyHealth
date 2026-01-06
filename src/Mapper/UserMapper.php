<?php

declare(strict_types=1);

namespace App\Mapper;

use App\ApiResource\UserResource;
use App\Entity\User;

readonly class UserMapper
{
    public function mapEntityToResource(User $user): UserResource
    {
        $resource = new UserResource();
        $resource->email = $user->getEmail();
        $resource->name = $user->getName();
        $resource->height = $user->getHeight();
        $resource->targetWeight = $user->getTargetWeight();
        $resource->dateOfBirth = $user->getDateOfBirth()->format('Y-m-d');

        return $resource;
    }
}
