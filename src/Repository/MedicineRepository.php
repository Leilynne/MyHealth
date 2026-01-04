<?php

namespace App\Repository;

use App\Entity\Medicine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Medicine>
 */
class MedicineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medicine::class);
    }

    public function save(Medicine $medicine): void
    {
        $this->getEntityManager()->persist($medicine);
        $this->getEntityManager()->flush();
    }

    /**
     * @return array<int, Medicine>
     */
    public function findByFilters(?string $partialName = null): array
    {
        $qb = $this->createQueryBuilder('m');

        if ($partialName) {
            $qb->andWhere('LOWER(m.name) LIKE LOWER(:partialName)')
                ->setParameter('partialName', '%' . $partialName . '%');
        }

        return $qb->orderBy('m.name', 'ASC')->getQuery()->getResult();
    }
}
