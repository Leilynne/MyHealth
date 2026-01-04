<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\BrandResource;
use App\Repository\BrandRepository;

/**
 * @implements ProviderInterface<BrandResource[]>
 */
readonly class BrandCollectionProvider implements ProviderInterface
{
    public function __construct(
        private BrandRepository $brandRepository,
    ) {
    }

    /**'
     * @return BrandResource[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $brands = $this->brandRepository->findAllBrands();

        $output = [];

        foreach ($brands as $brand) {
            $resource = new BrandResource();

            $resource->id = $brand->getId();
            $resource->name = $brand->getName();

            $output[] = $resource;
        }

        return $output;
    }
}
