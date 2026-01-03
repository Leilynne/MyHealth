<?php

namespace App\Repository;

use App\Entity\HeartParameters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HeartParameters>
 */
class HeartParametersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HeartParameters::class);
    }

    public function save(HeartParameters $heartParameters): void
    {
        $this->getEntityManager()->persist($heartParameters);
        $this->getEntityManager()->flush();
    }
}
