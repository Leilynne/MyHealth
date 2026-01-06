<?php

declare(strict_types=1);

namespace App\State\Exercise;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\ExerciseResource;
use App\Mapper\ExerciseMapper;
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
        private ExerciseMapper $exerciseMapper,
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

        return $this->exerciseMapper->mapEntityToResource($exercise);
    }
}
