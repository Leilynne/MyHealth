<?php

declare(strict_types=1);

namespace App\State\Food;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\FoodResource;
use App\Mapper\FoodMapper;
use App\Repository\FoodRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<FoodResource>
 */
readonly class FoodProvider implements ProviderInterface
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

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): FoodResource
    {
        $user = $this->getUserStrict();

        $food = $this->foodRepository->getByIdAndUserId((int) $uriVariables['id'], $user->getId());

        return $this->foodMapper->mapEntityToResource($food);
    }
}
