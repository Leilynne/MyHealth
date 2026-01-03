<?php

declare(strict_types=1);

namespace App\State\ExerciseSession;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\ExerciseSessionResource;
use App\Entity\ExerciseSession;
use App\Repository\ExerciseRepository;
use App\Repository\ExerciseSessionRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<ExerciseSessionResource>
 */
readonly class ExerciseSessionAddProcessor implements ProcessorInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private ExerciseSessionRepository $exerciseSessionRepository,
        private ExerciseRepository $exerciseRepository,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    /**'
     * @param ExerciseSessionResource $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ExerciseSessionResource
    {
        $user = $this->getUserStrict();
        $datetime = new \DateTimeImmutable();
        $exercise = $this->exerciseRepository->getByIdAndUserId($data->exerciseId, $user->getId());

        $exerciseSession = new ExerciseSession();
        $exerciseSession->setExercise($exercise);
        $exerciseSession->setUser($user);
        $exerciseSession->setPerformedAt($datetime);
        $exerciseSession->setDuration($data->duration);
        $this->exerciseSessionRepository->save($exerciseSession);

        $output = new ExerciseSessionResource();
        $output->duration = $exerciseSession->getDuration();
        $output->exerciseId = $exerciseSession->getExercise()->getId();

        return $output;
    }
}
