<?php

declare(strict_types=1);

namespace App\State\MedicationSchedule;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\MedicationScheduleResource;
use App\Repository\MedicationScheduleRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<MedicationScheduleResource[]>
 */
readonly class MedicationScheduleCollectionProvider implements ProviderInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private MedicationScheduleRepository $medicationScheduleRepository,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    /**'
     * @return MedicationScheduleResource[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $user = $this->getUserStrict();

        $medicationSchedules = $this->medicationScheduleRepository->findByUserId($user->getId());

        $output = [];

        foreach ($medicationSchedules as $medicationSchedule) {
            $resource = new MedicationScheduleResource();

            $resource->medicationScheduleId = $medicationSchedule->getId();
            $resource->timeOfDay = $medicationSchedule->getTimeOfDay()->value;
            $resource->medicineId = $medicationSchedule->getMedicine()->getId();
            $resource->medicineName = $medicationSchedule->getMedicine()->getName();

            $output[] = $resource;
        }

        return $output;
    }
}
