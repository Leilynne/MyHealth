<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\UserResource;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Bundle\SecurityBundle\Security;

readonly class CurrentUserProvider implements ProviderInterface
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): UserResource
    {
        $user = $this->security->getUser();

        if (false === $user instanceof User) {
            throw new UnauthorizedHttpException('Bearer');
        }

        $output = new UserResource();
        $output->email = $user->getEmail();
        $output->name = $user->getName();
        $output->height = $user->getHeight();
        $output->targetWeight = $user->getTargetWeight();
        $output->dateOfBirth = $user->getDateOfBirth()->format('Y-m-d');

        return $output;
    }
}
