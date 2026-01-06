<?php

declare(strict_types=1);

namespace App\State\Food;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\FoodResource;
use App\Entity\Food;
use App\Mapper\FoodMapper;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\FoodRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @implements ProcessorInterface<FoodResource>
 */
readonly class FoodAddProcessor implements ProcessorInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private FoodRepository $foodRepository,
        private BrandRepository $brandRepository,
        private CategoryRepository $categoryRepository,
        private FoodMapper $foodMapper,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    /**'
     * @param FoodResource $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): FoodResource
    {
        $user = $this->getUserStrict();
        $brand = $this->brandRepository->find($data->brandId) ?? throw new NotFoundHttpException('Brand not found');
        $category = $this->categoryRepository->find($data->categoryId) ?? throw new NotFoundHttpException('Category not found');

        $food = new Food();
        $food->setName($data->name);
        $food->setDescription($data->description);
        $food->setProteins($data->proteins);
        $food->setCarbohydrates($data->carbohydrates);
        $food->setFats($data->fats);
        $food->setCalories($data->calories);
        $food->setBrand($brand);
        $food->setCategory($category);
        $food->setUser($user);
        $food->setSystem(false);
        $this->foodRepository->save($food);

        return $this->foodMapper->mapEntityToResource($food);
    }
}
