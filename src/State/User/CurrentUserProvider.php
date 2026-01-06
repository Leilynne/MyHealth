<?php

declare(strict_types=1);

namespace App\State\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\UserResource;
use App\Mapper\UserMapper;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<UserResource>
 */
readonly class CurrentUserProvider implements ProviderInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private UserMapper $userMapper,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): UserResource
    {
        $user = $this->getUserStrict();

        return $this->userMapper->mapEntityToResource($user);
    }
}
