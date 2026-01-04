<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\CategoryResource;
use App\Repository\CategoryRepository;

/**
 * @implements ProviderInterface<CategoryResource[]>
 */
readonly class CategoryCollectionProvider implements ProviderInterface
{
    public function __construct(
        private CategoryRepository $categoryRepository,
    ) {
    }

    /**'
     * @return CategoryResource[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $brands = $this->categoryRepository->findAllCategories();

        $output = [];

        foreach ($brands as $brand) {
            $resource = new CategoryResource();

            $resource->id = $brand->getId();
            $resource->name = $brand->getName();

            $output[] = $resource;
        }

        return $output;
    }
}
