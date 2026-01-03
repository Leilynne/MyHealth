<?php

namespace App\Enum;

enum ArmEnum: string
{
    use BackedEnumCasesTrait;

    case Left = 'left';
    case Right = 'right';
}
