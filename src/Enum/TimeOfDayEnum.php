<?php

namespace App\Enum;

enum TimeOfDayEnum: string
{
    use BackedEnumCasesTrait;

    case Morning = 'morning';
    case Noon = 'noon';
    case Evening = 'evening';
}
