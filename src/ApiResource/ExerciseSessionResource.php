<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Filter\PeriodFilter;
use App\State\ExerciseSession\ExerciseSessionAddProcessor;
use App\State\ExerciseSession\ExerciseSessionCollectionProvider;
use App\State\ExerciseSession\ExerciseSessionTodayTotalKcalProvider;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '',
            paginationEnabled: false,
            normalizationContext: ['groups' => [self::OUTPUT]],
            filters: [
                PeriodFilter::class,
            ],
            provider: ExerciseSessionCollectionProvider::class,
        ),
        new Get(
            uriTemplate: '/today-total',
            normalizationContext: ['groups' => [self::TOTAL]],
            provider: ExerciseSessionTodayTotalKcalProvider::class,
        ),
        new Post(
            uriTemplate: '',
            normalizationContext: ['groups' => [self::OUTPUT]],
            denormalizationContext: ['groups' => [self::INPUT]],
            processor: ExerciseSessionAddProcessor::class,
        ),
    ],
    routePrefix: '/exercise-session',
    exceptionToStatus: [
        UnauthorizedHttpException::class => 401,
    ],
)]
class ExerciseSessionResource
{
    private const string INPUT = 'Input';
    private const string OUTPUT = 'Output';
    private const string TOTAL = 'Total';

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $exerciseId;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $duration;

    #[Groups([self::OUTPUT])]
    public int $totalKcal;

    #[Groups([self::OUTPUT])]
    public string $exerciseName;

    #[Groups([self::OUTPUT])]
    public string $performedAt;

    #[Groups([self::TOTAL])]
    public int $totalTodayKcal;
}
