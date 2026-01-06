<?php

namespace App\Repository;

use App\Entity\FoodIntake;
use App\Enum\FoodIntakeTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends ServiceEntityRepository<FoodIntake>
 */
class FoodIntakeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FoodIntake::class);
    }

    public function save(FoodIntake $foodIntake): void
    {
        $this->getEntityManager()->persist($foodIntake);
        $this->getEntityManager()->flush();
    }

    public function findByIdAndUserId(int $id, int $userId): FoodIntake
    {
        return $this->findOneBy(['id' => $id, 'user' => $userId]) ?? throw new NotFoundHttpException();
    }

    public function remove(FoodIntake $foodIntake): void
    {
        $this->getEntityManager()->remove($foodIntake);
        $this->getEntityManager()->flush();
    }

    /**
     * @return array<int, FoodIntake>
     */
    public function findByFilters(int $userId, ?\DateTimeImmutable $since = null): array
    {
        $queryBuilder = $this->createQueryBuilder('fi')
            ->leftJoin('fi.food', 'f')
            ->select('fi', 'f')
            ->where('fi.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('fi.type', 'DESC');

        if ($since !== null) {
            $queryBuilder->andWhere('fi.date >= :since')
                ->setParameter('since', $since);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function removeByFilters(int $userId, FoodIntakeTypeEnum $foodIntakeType, \DateTimeImmutable $date): void
    {
        $this->createQueryBuilder('fi')
            ->delete()
            ->where('fi.user = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('fi.type = :type')
            ->setParameter('type', $foodIntakeType)
            ->andWhere('fi.date = :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->getQuery()
            ->execute();
    }
}
