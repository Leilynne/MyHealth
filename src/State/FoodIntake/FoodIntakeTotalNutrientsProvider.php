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
readonly class FoodIntakeTotalNutrientsProvider implements ProviderInterface
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

        $fats = 0;
        $carbohydrates = 0;
        $proteins = 0;

        foreach ($foodIntakes as $foodIntake) {
            $fats += $foodIntake->getFood()->getFats() * $foodIntake->getAmount() / 100;
            $carbohydrates += $foodIntake->getFood()->getCarbohydrates() * $foodIntake->getAmount() / 100;
            $proteins += $foodIntake->getFood()->getProteins() * $foodIntake->getAmount() / 100;
        }

        $resource = new FoodIntakeResource();
        $resource->totalFats = (int) $fats;
        $resource->totalCarbohydrates = (int) $carbohydrates;
        $resource->totalProteins = (int) $proteins;

        return $resource;
    }
}
