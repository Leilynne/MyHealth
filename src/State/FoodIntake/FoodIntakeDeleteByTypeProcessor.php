<?php

declare(strict_types=1);

namespace App\State\FoodIntake;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Filter\FoodIntakeFilter;
use App\ApiResource\FoodIntakeResource;
use App\Enum\FoodIntakeTypeEnum;
use App\Repository\FoodIntakeRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<void>
 */
readonly class FoodIntakeDeleteByTypeProcessor implements ProcessorInterface
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
     * @param FoodIntakeResource $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $user = $this->getUserStrict();
        $foodIntakeType = FoodIntakeTypeEnum::from($context['filters'][FoodIntakeFilter::NAME] ?? '');
        $date = new \DateTimeImmutable('today');

        $this->foodIntakeRepository->removeByFilters($user->getId(), $foodIntakeType, $date);
    }
}
