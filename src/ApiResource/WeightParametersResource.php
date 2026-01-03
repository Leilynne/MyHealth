<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Filter\PeriodFilter;
use App\State\WeightParameters\WeightParametersAddProcessor;
use App\State\WeightParameters\WeightParametersCollectionProvider;
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
            provider: WeightParametersCollectionProvider::class,
        ),
        new Post(
            uriTemplate: '',
            normalizationContext: ['groups' => [self::OUTPUT]],
            denormalizationContext: ['groups' => [self::INPUT]],
            processor: WeightParametersAddProcessor::class,
        ),
    ],
    routePrefix: '/weight-parameters',
    exceptionToStatus: [
        UnauthorizedHttpException::class => 401,
    ],
)]
class WeightParametersResource
{
    private const string INPUT = 'Input';
    private const string OUTPUT = 'Output';

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $weight;

    #[Groups([self::OUTPUT])]
    public string $datetime;
}
