<?php

declare(strict_types=1);

namespace App\State\Exercise;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\ExerciseResource;
use App\Repository\ExerciseRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<ExerciseResource>
 */
readonly class ExerciseProvider implements ProviderInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private ExerciseRepository $exerciseRepository,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ExerciseResource
    {
        $user = $this->getUserStrict();

        $exercise = $this->exerciseRepository->getByIdAndUserId((int) $uriVariables['id'], $user->getId());

        $resource = new ExerciseResource();
        $resource->id = $exercise->getId();
        $resource->kcalPerHour = $exercise->getKcalPerHour();
        $resource->name = $exercise->getName();
        $resource->description = $exercise->getDescription();
        $resource->isSystem = $exercise->isSystem();

        return $resource;
    }
}
