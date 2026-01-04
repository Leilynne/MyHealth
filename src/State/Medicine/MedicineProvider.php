<?php

declare(strict_types=1);

namespace App\State\Medicine;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\MedicineResource;
use App\Repository\MedicineRepository;

/**
 * @implements ProviderInterface<MedicineResource>
 */
readonly class MedicineProvider implements ProviderInterface
{
    public function __construct(
        private MedicineRepository $medicineRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): MedicineResource
    {
        $medicine = $this->medicineRepository->findById($uriVariables['id']);

        $resource = new MedicineResource();
        $resource->id = $medicine->getId();
        $resource->dose = $medicine->getDose();
        $resource->name = $medicine->getName();
        $resource->description = $medicine->getDescription();

        return $resource;
    }
}
