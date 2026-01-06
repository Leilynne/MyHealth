<?php

declare(strict_types=1);

namespace App\State\Medicine;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Filter\NameFilter;
use App\ApiResource\MedicineResource;
use App\Mapper\MedicineMapper;
use App\Repository\MedicineRepository;

/**
 * @implements ProviderInterface<MedicineResource[]>
 */
readonly class MedicineCollectionProvider implements ProviderInterface
{
    public function __construct(
        private MedicineRepository $medicineRepository,
        private MedicineMapper $medicineMapper,
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
            $output[] = $this->medicineMapper->mapEntityToResource($medicine);
        }

        return $output;
    }
}
