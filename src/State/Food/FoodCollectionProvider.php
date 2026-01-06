<?php

declare(strict_types=1);

namespace App\State\Food;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Filter\NameFilter;
use App\ApiResource\FoodResource;
use App\Mapper\FoodMapper;
use App\Repository\FoodRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<FoodResource[]>
 */
readonly class FoodCollectionProvider implements ProviderInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private FoodRepository $foodRepository,
        private FoodMapper $foodMapper,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    /**'
     * @return FoodResource[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $user = $this->getUserStrict();

        $nameFilter = $context['filters'][NameFilter::NAME] ?? null;

        $foods = $this->foodRepository->findByFilters($user->getId(), $nameFilter);

        $output = [];

        foreach ($foods as $food) {
            $output[] = $this->foodMapper->mapEntityToResource($food);
        }

        return $output;
    }
}
