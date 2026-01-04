<?php

declare(strict_types=1);

namespace App\State\MedicationSchedule;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\MedicationScheduleResource;
use App\Repository\MedicationScheduleRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<void>
 */
readonly class MedicationScheduleDeleteProcessor implements ProcessorInterface
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

    /**
     * @param MedicationScheduleResource $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $user = $this->getUserStrict();

        $medicationSchedule = $this->medicationScheduleRepository->findByIdAndUserId((int) $uriVariables['id'], $user->getId());
        $this->medicationScheduleRepository->remove($medicationSchedule);
    }
}
