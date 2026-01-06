<?php

namespace App\Repository;

use App\Entity\Food;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends ServiceEntityRepository<Food>
 */
class FoodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Food::class);
    }

    public function save(Food $food): void
    {
        $this->getEntityManager()->persist($food);
        $this->getEntityManager()->flush();
    }

    public function getByIdAndUserId(int $foodId, int $userId): Food
    {
        $queryBuilder = $this->createQueryBuilder('f');
        $queryBuilder->leftJoin('f.category', 'c')
            ->leftJoin('f.brand', 'b')
            ->select('f', 'c', 'b')
            ->andWhere('f.id = :foodId')
            ->setParameter('foodId', $foodId)
            ->andWhere(
                $queryBuilder->expr()->orX(
                    'f.system = :isSystem',
                    'f.user = :userId'
                ),
            )->setParameter('userId', $userId)
            ->setParameter('isSystem', true);

        return $queryBuilder->getQuery()->getOneOrNullResult() ?? throw new NotFoundHttpException();
    }

    /**
     * @return array<int, Food>
     */
    public function findByFilters(int $userId, ?string $partialName = null): array
    {
        $queryBuilder = $this->createQueryBuilder('f')
            ->leftJoin('f.category', 'c')
            ->leftJoin('f.brand', 'b')
            ->select('f', 'c', 'b');

        $expr = $queryBuilder->expr()->orX(
            'f.system = :isSystem',
            'f.user = :userId'
        );

        $queryBuilder->where($expr)
            ->setParameter('userId', $userId)
            ->setParameter('isSystem', true);

        if (null !== $partialName) {
            $partialName = trim(mb_strtolower($partialName));
            $queryBuilder->andWhere('LOWER(f.name) LIKE :partialName')
                ->setParameter('partialName', "%$partialName%");
        }

        $queryBuilder->orderBy('f.name', 'ASC');

        return $queryBuilder->getQuery()
            ->getResult();
    }
}
