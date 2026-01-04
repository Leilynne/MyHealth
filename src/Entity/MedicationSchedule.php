<?php

namespace App\Entity;

use App\Enum\TimeOfDayEnum;
use App\Repository\MedicationScheduleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedicationScheduleRepository::class)]
class MedicationSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private TimeOfDayEnum $timeOfDay;

    #[ORM\ManyToOne(targetEntity: Medicine::class, inversedBy: 'schedules')]
    private Medicine $medicine;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'medicationSchedules')]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTimeOfDay(): TimeOfDayEnum
    {
        return $this->timeOfDay;
    }

    public function getMedicine(): Medicine
    {
        return $this->medicine;
    }

    public function setTimeOfDay(TimeOfDayEnum $timeOfDay): void
    {
        $this->timeOfDay = $timeOfDay;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setMedicine(Medicine $medicine): void
    {
        $this->medicine = $medicine;
    }
}
