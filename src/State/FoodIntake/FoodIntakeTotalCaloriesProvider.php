<?php

declare(strict_types=1);

namespace App\State\FoodIntake;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Filter\PeriodFilter;
use App\ApiResource\FoodIntakeResource;
use App\Enum\PeriodEnum;
use App\Repository\FoodIntakeRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<FoodIntakeResource>
 */
readonly class FoodIntakeTotalCaloriesProvider implements ProviderInterface
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

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): FoodIntakeResource
    {
        $user = $this->getUserStrict();
        $periodValue = $context['filters'][PeriodFilter::NAME] ?? '';
        $since = match (PeriodEnum::tryFrom($periodValue)) {
            null => null,
            PeriodEnum::Day => new \DateTimeImmutable('today'),
            PeriodEnum::Week => new \DateTimeImmutable('-1 week'),
            PeriodEnum::Month => new \DateTimeImmutable('-1 month'),
        };

        $foodIntakes = $this->foodIntakeRepository->findByFilters($user->getId(), $since);

        $calories = 0;

        foreach ($foodIntakes as $foodIntake) {
            $calories += $foodIntake->getFood()->getCalories() * $foodIntake->getAmount() / 100;
        }

        $resource = new FoodIntakeResource();
        $resource->totalCalories = (int) $calories;

        return $resource;
    }
}
