<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Filter\NameFilter;
use App\State\Food\FoodAddProcessor;
use App\State\Food\FoodCollectionProvider;
use App\State\Food\FoodProvider;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/food',
            paginationEnabled: false,
            normalizationContext: ['groups' => [self::OUTPUT]],
            filters: [
                NameFilter::class,
            ],
            provider: FoodCollectionProvider::class,
        ),
        new Get(
            uriTemplate: '/food/{id}',
            normalizationContext: ['groups' => [self::OUTPUT]],
            provider: FoodProvider::class,
        ),
        new Post(
            uriTemplate: '/food',
            normalizationContext: ['groups' => [self::OUTPUT]],
            denormalizationContext: ['groups' => [self::INPUT]],
            processor: FoodAddProcessor::class,
        ),
    ],
    exceptionToStatus: [
        UnauthorizedHttpException::class => 401,
    ],
)]
class FoodResource
{
    private const string INPUT = 'Input';
    private const string OUTPUT = 'Output';

    #[Groups([self::OUTPUT])]
    #[ApiProperty(identifier: true)]
    public int $id;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $name;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Length(max: 255)]
    public string $description;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $proteins;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $fats;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $carbohydrates;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $calories;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $brandId;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $categoryId;

    #[Groups([self::OUTPUT])]
    public string $brandName;

    #[Groups([self::OUTPUT])]
    public string $categoryName;

    #[Groups([self::OUTPUT])]
    public bool $isSystem;
}
