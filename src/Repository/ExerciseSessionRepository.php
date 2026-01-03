<?php

namespace App\Repository;

use App\Entity\ExerciseSession;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExerciseSession>
 */
class ExerciseSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExerciseSession::class);
    }

    public function save(ExerciseSession $exerciseSession): void
    {
        $this->getEntityManager()->persist($exerciseSession);
        $this->getEntityManager()->flush();
    }

    /**
     * @return array<int, ExerciseSession>
     */
    public function findByFilters(int $userId, ?\DateTimeImmutable $since = null): array
    {
        $queryBuilder = $this->createQueryBuilder('es');

        $queryBuilder->where('es.user = :userId')
            ->setParameter('userId', $userId);

        if (null !== $since) {
            $queryBuilder->andWhere('es.performedAt >= :since')
                ->setParameter('since', $since);
        }

        $queryBuilder->orderBy('es.performedAt', 'DESC');

        return $queryBuilder->getQuery()
            ->getResult();
    }
}
