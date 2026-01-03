<?php

namespace App\ApiResource\Filter;

use ApiPlatform\Metadata\FilterInterface;

class NameFilter implements FilterInterface
{
    public const string NAME = 'name';

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
                'description' => 'allows to filter by name',
                'schema' => [
                    'type' => 'string',
                ],
                'openapi' => [
                    'allowEmptyValue' => false,
                ],
            ],
        ];
    }
}
