<?php

declare(strict_types=1);

namespace App\State\Exercise;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\ExerciseResource;
use App\ApiResource\Filter\NameFilter;
use App\Repository\ExerciseRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<ExerciseResource[]>
 */
readonly class ExerciseCollectionProvider implements ProviderInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private ExerciseRepository $exerciseRepository,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    /**'
     * @return ExerciseResource[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $user = $this->getUserStrict();

        $nameFilter = $context['filters'][NameFilter::NAME] ?? null;

        $exercises = $this->exerciseRepository->findByFilters($user->getId(), $nameFilter);

        $output = [];

        foreach ($exercises as $exercise) {
            $resource = new ExerciseResource();

            $resource->exerciseId = $exercise->getId();
            $resource->kcalPerHour = $exercise->getKcalPerHour();
            $resource->name = $exercise->getName();
            $resource->description = $exercise->getDescription();
            $resource->isSystem = $exercise->isSystem();

            $output[] = $resource;
        }

        return $output;
    }
}
