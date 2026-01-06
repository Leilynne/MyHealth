<?php

namespace App\Enum;

enum FoodIntakeTypeEnum: string
{
    use BackedEnumCasesTrait;

    case Breakfast = 'breakfast';
    case Lunch = 'lunch';
    case Dinner = 'dinner';
    case Snack = 'snack';
}
