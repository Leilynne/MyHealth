<?php

namespace App\Entity;

use App\Enum\ArmEnum;
use App\Repository\HeartParametersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeartParametersRepository::class)]
class HeartParameters
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private ArmEnum $arm;

    #[ORM\Column]
    private int $heartBeat;

    #[ORM\Column]
    private int $systola;

    #[ORM\Column]
    private int $diastola;

    #[ORM\Column]
    private \DateTimeImmutable $date;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'heartParameters')]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function setArm(ArmEnum $arm): void
    {
        $this->arm = $arm;
    }

    public function setHeartBeat(int $heartBeat): void
    {
        $this->heartBeat = $heartBeat;
    }

    public function setSystola(int $systola): void
    {
        $this->systola = $systola;
    }

    public function setDiastola(int $diastola): void
    {
        $this->diastola = $diastola;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setDatetime(\DateTimeImmutable $datetime): void
    {
        $this->date = $datetime;
    }

    public function getArm(): ArmEnum
    {
        return $this->arm;
    }

    public function getSystola(): int
    {
        return $this->systola;
    }

    public function getDiastola(): int
    {
        return $this->diastola;
    }

    public function getDatetime(): \DateTimeImmutable
    {
        return $this->date;
    }
}
