<?php

namespace App\Enum;

/**
 * @mixin \BackedEnum
 */
trait BackedEnumCasesTrait
{
    /**
     * @return array<int, string>
     */
    public static function stringCases(): array
    {
        return array_values(array_map(static fn (self $case): string => (string) $case->value, self::cases()));
    }
}
