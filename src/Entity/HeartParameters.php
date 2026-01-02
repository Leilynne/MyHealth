<?php

namespace App\Entity;
use App\Enum\ArmEnum;
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
}
