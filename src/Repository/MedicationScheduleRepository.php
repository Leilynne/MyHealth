<?php

namespace App\Repository;

use App\Entity\MedicationSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MedicationSchedule>
 */
class MedicationScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedicationSchedule::class);
    }

    public function save(MedicationSchedule $medicationSchedule): void
    {
        $this->getEntityManager()->persist($medicationSchedule);
        $this->getEntityManager()->flush();
    }

    /**
     * @return array<int, MedicationSchedule>
     */
    public function findByUserId(int $userId): array
    {
        $qb = $this->createQueryBuilder('ms')
            ->leftJoin('ms.medicine', 'm')
            ->select('ms', 'm')
            ->where('ms.user = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('ms.timeOfDay', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
