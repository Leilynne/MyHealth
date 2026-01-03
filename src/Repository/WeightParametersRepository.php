<?php

namespace App\Repository;

use App\Entity\WeightParameters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeightParameters>
 */
class WeightParametersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeightParameters::class);
    }

    public function save(WeightParameters $weightParameters): void
    {
        $this->getEntityManager()->persist($weightParameters);
        $this->getEntityManager()->flush();
    }

    /**
     * @return array<int, WeightParameters>
     */
    public function findByUserAndSince(int $userId, ?\DateTimeImmutable $since = null): array
    {
        $queryBuilder = $this->createQueryBuilder('wp')
            ->where('wp.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('wp.date', 'DESC');

        if ($since !== null) {
            $queryBuilder->andWhere('wp.date >= :since')
                ->setParameter('since', $since);
        }

        return $queryBuilder->getQuery()
            ->getResult();
    }
}
