<?php

namespace App\Repository;

use App\Entity\Exercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
                    'e.system = true',
                    'e.user = :userId'
                ),
            )->setParameter('userId', $userId);

        return $queryBuilder->getQuery()->getSingleResult();
    }

    /**
     * @return array<int, Exercise>
     */
    public function findByFilters(int $userId, ?string $partialName = null): array
    {
        $queryBuilder = $this->createQueryBuilder('e');

        $expr = $queryBuilder->expr()->orX(
            'e.system = true',
            'e.user = :userId'
        );

        $queryBuilder->where($expr)
            ->setParameter('userId', $userId);

        if (null !== $partialName) {
            $queryBuilder->andWhere('LOWER(e.name) LIKE LOWER(:partialName)')
                ->setParameter('partialName', '%' . $partialName . '%');
        }

        $queryBuilder->orderBy('e.name', 'ASC');

        return $queryBuilder->getQuery()
            ->getResult();
    }
}
