<?php

namespace App\ApiResource\Filter;

use ApiPlatform\Metadata\FilterInterface;
use App\Enum\FoodIntakeTypeEnum;

class FoodIntakeFilter implements FilterInterface
{
    public const string NAME = 'type';

    /**
     * @return array<string, array<string, array<string, array<int, string>|bool|string>|string|false>>
     */
    public function getDescription(string $resourceClass): array
    {
        return [
            self::NAME => [
                'property' => self::NAME,
                'type' => 'string',
                'required' => false,
                'description' => 'allows to filter by food intake type',
                'schema' => [
                    'type' => 'string',
                    'enum' => array_map(static fn (FoodIntakeTypeEnum $case): string => $case->value, FoodIntakeTypeEnum::cases()),
                ],
                'openapi' => [
                    'allowEmptyValue' => false,
                ],
            ],
        ];
    }
}
