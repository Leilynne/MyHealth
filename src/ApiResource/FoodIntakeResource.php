<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\QueryParameter;
use App\ApiResource\Filter\FoodIntakeFilter;
use App\ApiResource\Filter\PeriodFilter;
use App\Enum\FoodIntakeTypeEnum;
use App\State\FoodIntake\FoodIntakeAddProcessor;
use App\State\FoodIntake\FoodIntakeCollectionProvider;
use App\State\FoodIntake\FoodIntakeDeleteByTypeProcessor;
use App\State\FoodIntake\FoodIntakeDeleteProcessor;
use App\State\FoodIntake\FoodIntakeTotalCaloriesProvider;
use App\State\FoodIntake\FoodIntakeTotalNutrientsProvider;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/food-intake',
            paginationEnabled: false,
            normalizationContext: ['groups' => [self::OUTPUT]],
            provider: FoodIntakeCollectionProvider::class,
        ),
        new Delete(
            uriTemplate: '/food-intake',
            processor: FoodIntakeDeleteByTypeProcessor::class,
            parameters: [
                FoodIntakeFilter::NAME => new QueryParameter(filter: FoodIntakeFilter::class, required: true),
            ],
        ),
        new Delete(
            uriTemplate: '/food-intake/{id}',
            requirements: ['id' => '\d+'],
            processor: FoodIntakeDeleteProcessor::class,
        ),
        new Post(
            uriTemplate: '/food-intake',
            normalizationContext: ['groups' => [self::OUTPUT]],
            denormalizationContext: ['groups' => [self::INPUT]],
            processor: FoodIntakeAddProcessor::class,
        ),
        new Get(
            uriTemplate: '/food-intake/total-calories',
            normalizationContext: ['groups' => [self::TOTAL_CALORIES]],
            provider: FoodIntakeTotalCaloriesProvider::class,
            parameters: [
                PeriodFilter::NAME => new QueryParameter(filter: PeriodFilter::class, required: true),
            ],
        ),
        new Get(
            uriTemplate: '/food-intake/nutrients',
            normalizationContext: ['groups' => [self::TOTAL_NUTRIENTS]],
            provider: FoodIntakeTotalNutrientsProvider::class,
            parameters: [
                PeriodFilter::NAME => new QueryParameter(filter: PeriodFilter::class, required: true),
            ],
        ),
    ],
    exceptionToStatus: [
        UnauthorizedHttpException::class => 401,
    ],
)]
class FoodIntakeResource
{
    private const string INPUT = 'Input';
    private const string OUTPUT = 'Output';
    private const string TOTAL_CALORIES = 'TotalCalories';
    private const string TOTAL_NUTRIENTS = 'TotalNutrients';

    #[Groups([self::OUTPUT])]
    #[ApiProperty(identifier: true)]
    public int $id;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\NotBlank]
    #[Assert\Choice(callback: FoodIntakeTypeEnum::class . '::stringCases')]
    public string $type;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $foodId;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $amount;

    #[Groups([self::OUTPUT])]
    public string $foodName;

    #[Groups([self::OUTPUT])]
    public string $date;

    #[Groups([self::TOTAL_CALORIES])]
    public int $totalCalories;

    #[Groups([self::TOTAL_NUTRIENTS])]
    public int $totalFats;

    #[Groups([self::TOTAL_NUTRIENTS])]
    public int $totalCarbohydrates;

    #[Groups([self::TOTAL_NUTRIENTS])]
    public int $totalProteins;
}
