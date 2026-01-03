<?php

namespace App\Enum;

enum PeriodEnum: string
{
    use BackedEnumCasesTrait;

    case Day = 'day';
    case Week = 'week';
    case Month = 'month';
}
