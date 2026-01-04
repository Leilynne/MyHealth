<?php

declare(strict_types=1);

namespace App\State\ExerciseSession;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\ExerciseSessionResource;
use App\ApiResource\Filter\PeriodFilter;
use App\Enum\PeriodEnum;
use App\Repository\ExerciseSessionRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<ExerciseSessionResource[]>
 */
readonly class ExerciseSessionCollectionProvider implements ProviderInterface
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

    /**'
     * @return ExerciseSessionResource[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $user = $this->getUserStrict();

        $periodFilter = $context['filters'][PeriodFilter::NAME] ?? '';

        $since = match (PeriodEnum::tryFrom($periodFilter)) {
            null => null,
            PeriodEnum::Day => new \DateTimeImmutable('today'),
            PeriodEnum::Week => new \DateTimeImmutable('-1 week'),
            PeriodEnum::Month => new \DateTimeImmutable('-1 month'),
        };

        $exerciseSessions = $this->exerciseSessionRepository->findByFilters($user->getId(), $since);

        $output = [];

        foreach ($exerciseSessions as $exerciseSession) {
            $resource = new ExerciseSessionResource();

            $resource->duration = $exerciseSession->getDuration();
            $resource->exerciseId = $exerciseSession->getExercise()->getId();
            $resource->totalKcal = (int) ($exerciseSession->getExercise()->getKcalPerHour() * $exerciseSession->getDuration() / 60);
            $resource->exerciseName = $exerciseSession->getExercise()->getName();
            $resource->performedAt = $exerciseSession->getPerformedAt()->format('Y-m-d H:i:s');

            $output[] = $resource;
        }

        return $output;
    }
}
