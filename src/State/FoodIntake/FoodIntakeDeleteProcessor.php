<?php

declare(strict_types=1);

namespace App\State\FoodIntake;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\MedicationScheduleResource;
use App\Repository\FoodIntakeRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<void>
 */
readonly class FoodIntakeDeleteProcessor implements ProcessorInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private FoodIntakeRepository $foodIntakeRepository,
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

        $foodIntake = $this->foodIntakeRepository->findByIdAndUserId((int) $uriVariables['id'], $user->getId());
        $this->foodIntakeRepository->remove($foodIntake);
    }
}
