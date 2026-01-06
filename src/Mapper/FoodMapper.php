<?php

declare(strict_types=1);

namespace App\Mapper;

use App\ApiResource\FoodResource;
use App\Entity\Food;

readonly class FoodMapper
{
    public function mapEntityToResource(Food $food): FoodResource
    {
        $resource = new FoodResource();
        $resource->id = $food->getId();
        $resource->name = $food->getName();
        $resource->description = $food->getDescription();
        $resource->proteins = $food->getProteins();
        $resource->carbohydrates = $food->getCarbohydrates();
        $resource->fats = $food->getFats();
        $resource->calories = $food->getCalories();
        $resource->brandId = $food->getBrand()->getId();
        $resource->categoryId = $food->getCategory()->getId();
        $resource->isSystem = $food->isSystem();
        $resource->brandName = $food->getBrand()->getName();
        $resource->categoryName = $food->getCategory()->getName();

        return $resource;
    }
}
