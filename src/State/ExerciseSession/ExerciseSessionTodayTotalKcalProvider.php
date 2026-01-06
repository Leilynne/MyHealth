<?php

declare(strict_types=1);

namespace App\State\ExerciseSession;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\ExerciseSessionResource;
use App\Repository\ExerciseSessionRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<ExerciseSessionResource>
 */
readonly class ExerciseSessionTodayTotalKcalProvider implements ProviderInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private ExerciseSessionRepository $exerciseSessionRepository,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ExerciseSessionResource
    {
        $user = $this->getUserStrict();
        $exerciseSessions = $this->exerciseSessionRepository->findByFilters($user->getId(), new \DateTimeImmutable('today'));

        $todayKcal = 0;

        foreach ($exerciseSessions as $exerciseSession) {
            $todayKcal += $exerciseSession->getTotalKcal();
        }

        $output = new ExerciseSessionResource();
        $output->totalTodayKcal = (int) $todayKcal;

        return $output;
    }
}
