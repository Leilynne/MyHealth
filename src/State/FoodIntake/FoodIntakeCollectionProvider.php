<?php

declare(strict_types=1);

namespace App\State\FoodIntake;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\FoodIntakeResource;
use App\Mapper\FoodIntakeMapper;
use App\Repository\FoodIntakeRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<FoodIntakeResource[]>
 */
readonly class FoodIntakeCollectionProvider implements ProviderInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private FoodIntakeRepository $foodIntakeRepository,
        private FoodIntakeMapper $foodIntakeMapper,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    /**'
     * @return FoodIntakeResource[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $user = $this->getUserStrict();
        $today = new \DateTimeImmutable('today');

        $foodIntakes = $this->foodIntakeRepository->findByFilters($user->getId(), $today);

        $output = [];

        foreach ($foodIntakes as $foodIntake) {
            $output[] = $this->foodIntakeMapper->mapEntityToResource($foodIntake);
        }

        return $output;
    }
}
