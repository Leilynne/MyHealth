<?php

declare(strict_types=1);

namespace App\State\FoodIntake;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\FoodIntakeResource;
use App\Entity\FoodIntake;
use App\Enum\FoodIntakeTypeEnum;
use App\Mapper\FoodIntakeMapper;
use App\Repository\FoodIntakeRepository;
use App\Repository\FoodRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<FoodIntakeResource>
 */
readonly class FoodIntakeAddProcessor implements ProcessorInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private FoodIntakeRepository $foodIntakeRepository,
        private FoodRepository $foodRepository,
        private FoodIntakeMapper $foodIntakeMapper,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    /**
     * @param FoodIntakeResource $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): FoodIntakeResource
    {
        $user = $this->getUserStrict();
        $date = new \DateTimeImmutable();

        $food = $this->foodRepository->getByIdAndUserId($data->foodId, $user->getId());

        $foodIntake = new FoodIntake();
        $foodIntake->setFood($food);
        $foodIntake->setUser($user);
        $foodIntake->setAmount($data->amount);
        $foodIntake->setDate($date);
        $foodIntake->setType(FoodIntakeTypeEnum::from($data->type));
        $this->foodIntakeRepository->save($foodIntake);

        return $this->foodIntakeMapper->mapEntityToResource($foodIntake);
    }
}
