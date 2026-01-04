<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Filter\NameFilter;
use App\State\Exercise\ExerciseAddProcessor;
use App\State\Exercise\ExerciseCollectionProvider;
use App\State\Exercise\ExerciseProvider;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/exercise',
            paginationEnabled: false,
            normalizationContext: ['groups' => [self::OUTPUT]],
            filters: [
                NameFilter::class,
            ],
            provider: ExerciseCollectionProvider::class,
        ),
        new Get(
            uriTemplate: '/exercise/{id}',
            normalizationContext: ['groups' => [self::OUTPUT]],
            provider: ExerciseProvider::class,
        ),
        new Post(
            uriTemplate: '/exercise',
            normalizationContext: ['groups' => [self::OUTPUT]],
            denormalizationContext: ['groups' => [self::INPUT]],
            processor: ExerciseAddProcessor::class,
        ),
    ],
    exceptionToStatus: [
        UnauthorizedHttpException::class => 401,
    ],
)]
class ExerciseResource
{
    private const string INPUT = 'Input';
    private const string OUTPUT = 'Output';

    #[Groups([self::OUTPUT])]
    #[ApiProperty(identifier: true)]
    public int $id;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $kcalPerHour;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $name;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Length(max: 255)]
    public string $description;

    #[Groups([self::OUTPUT])]
    public bool $isSystem;
}
