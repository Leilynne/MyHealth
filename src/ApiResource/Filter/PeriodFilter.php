<?php

namespace App\ApiResource\Filter;

use ApiPlatform\Metadata\FilterInterface;
use App\Enum\PeriodEnum;

class PeriodFilter implements FilterInterface
{
    public const string NAME = 'period';

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
                'description' => 'allows to filter by period',
                'schema' => [
                    'type' => 'string',
                    'enum' => array_map(static fn (PeriodEnum $case): string => $case->value, PeriodEnum::cases()),
                ],
                'openapi' => [
                    'allowEmptyValue' => false,
                ],
            ],
        ];
    }
}
