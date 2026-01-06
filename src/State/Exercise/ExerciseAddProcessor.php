<?php

declare(strict_types=1);

namespace App\State\Exercise;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\ExerciseResource;
use App\Entity\Exercise;
use App\Mapper\ExerciseMapper;
use App\Repository\ExerciseRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<ExerciseResource>
 */
readonly class ExerciseAddProcessor implements ProcessorInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private ExerciseRepository $exerciseRepository,
        private ExerciseMapper $exerciseMapper,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    /**'
     * @param ExerciseResource $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ExerciseResource
    {
        $user = $this->getUserStrict();
        $exercise = new Exercise();
        $exercise->setName($data->name);
        $exercise->setDescription($data->description);
        $exercise->setKcalPerHour($data->kcalPerHour);
        $exercise->setUser($user);
        $exercise->setSystem(false);
        $this->exerciseRepository->save($exercise);

        return $this->exerciseMapper->mapEntityToResource($exercise);
    }
}
