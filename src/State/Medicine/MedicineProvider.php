<?php

declare(strict_types=1);

namespace App\State\Medicine;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\MedicineResource;
use App\Mapper\MedicineMapper;
use App\Repository\MedicineRepository;

/**
 * @implements ProviderInterface<MedicineResource>
 */
readonly class MedicineProvider implements ProviderInterface
{
    public function __construct(
        private MedicineRepository $medicineRepository,
        private MedicineMapper $medicineMapper,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): MedicineResource
    {
        $medicine = $this->medicineRepository->findById((int) $uriVariables['id']);

        return $this->medicineMapper->mapEntityToResource($medicine);
    }
}
