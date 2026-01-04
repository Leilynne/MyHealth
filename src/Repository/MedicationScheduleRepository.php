<?php

namespace App\Repository;

use App\Entity\MedicationSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function findByIdAndUserId(int $id, int $userId): MedicationSchedule
    {
        return $this->findOneBy(['id' => $id, 'user' => $userId]) ?? throw new NotFoundHttpException();
    }

    public function remove(MedicationSchedule $medicationSchedule): void
    {
        $this->getEntityManager()->remove($medicationSchedule);
        $this->getEntityManager()->flush();
    }
}
