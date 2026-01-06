<?php

namespace App\Repository;

use App\Entity\Exercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends ServiceEntityRepository<Exercise>
 */
class ExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercise::class);
    }

    public function save(Exercise $exercise): void
    {
        $this->getEntityManager()->persist($exercise);
        $this->getEntityManager()->flush();
    }

    public function getByIdAndUserId(int $exerciseId, int $userId): Exercise
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder->andWhere('e.id = :exerciseId')
            ->setParameter('exerciseId', $exerciseId)
            ->andWhere(
                $queryBuilder->expr()->orX(
                    'e.system = :isSystem',
                    'e.user = :userId'
                ),
            )->setParameter('userId', $userId)
            ->setParameter('isSystem', true);

        return $queryBuilder->getQuery()->getOneOrNullResult() ?? throw new NotFoundHttpException();
    }

    /**
     * @return array<int, Exercise>
     */
    public function findByFilters(int $userId, ?string $partialName = null): array
    {
        $queryBuilder = $this->createQueryBuilder('e');

        $expr = $queryBuilder->expr()->orX(
            'e.system = :isSystem',
            'e.user = :userId'
        );

        $queryBuilder->where($expr)
            ->setParameter('userId', $userId)
            ->setParameter('isSystem', true);

        if (null !== $partialName) {
            $partialName = trim(mb_strtolower($partialName));
            $queryBuilder->andWhere('LOWER(e.name) LIKE :partialName')
                ->setParameter('partialName', "%$partialName%");
        }

        $queryBuilder->orderBy('e.name', 'ASC');

        return $queryBuilder->getQuery()
            ->getResult();
    }
}
