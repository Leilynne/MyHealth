<?php

declare(strict_types=1);

namespace App\State\MedicationSchedule;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\MedicationScheduleResource;
use App\Entity\MedicationSchedule;
use App\Enum\TimeOfDayEnum;
use App\Mapper\MedicationScheduleMapper;
use App\Repository\MedicationScheduleRepository;
use App\Repository\MedicineRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<MedicationScheduleResource>
 */
readonly class MedicationScheduleAddProcessor implements ProcessorInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private MedicationScheduleRepository $medicationScheduleRepository,
        private MedicineRepository $medicineRepository,
        private MedicationScheduleMapper $medicationScheduleMapper,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    /**
     * @param MedicationScheduleResource $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): MedicationScheduleResource
    {
        $user = $this->getUserStrict();

        $medicine = $this->medicineRepository->findById($data->medicineId);

        $medicationSchedule = new MedicationSchedule();
        $medicationSchedule->setTimeOfDay(TimeOfDayEnum::from($data->timeOfDay));
        $medicationSchedule->setUser($user);
        $medicationSchedule->setMedicine($medicine);
        $this->medicationScheduleRepository->save($medicationSchedule);

        return $this->medicationScheduleMapper->mapEntityToResource($medicationSchedule);
    }
}
