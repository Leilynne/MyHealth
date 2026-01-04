<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Filter\NameFilter;
use App\ApiResource\MedicineResource;
use App\Repository\MedicineRepository;

/**
 * @implements ProviderInterface<MedicineResource[]>
 */
readonly class MedicineCollectionProvider implements ProviderInterface
{
    public function __construct(
        private MedicineRepository $medicineRepository,
    ) {
    }

    /**'
     * @return MedicineResource[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $nameFilter = $context['filters'][NameFilter::NAME] ?? null;

        $medicines = $this->medicineRepository->findByFilters($nameFilter);

        $output = [];

        foreach ($medicines as $medicine) {
            $resource = new MedicineResource();

            $resource->medicineId = $medicine->getId();
            $resource->dose = $medicine->getDose();
            $resource->name = $medicine->getName();
            $resource->description = $medicine->getDescription();

            $output[] = $resource;
        }

        return $output;
    }
}
