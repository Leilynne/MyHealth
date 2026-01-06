<?php

namespace App\Entity;
use App\Repository\WeightParametersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeightParametersRepository::class)]
class WeightParameters
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private int $realWeight;

    #[ORM\Column]
    private \DateTimeImmutable $date;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'weightParameters')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function setWeight(int $weight): void
    {
        $this->realWeight = $weight;
    }

    public function setDatetime(\DateTimeImmutable $datetime): void
    {
        $this->date = $datetime;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getWeight(): int
    {
        return $this->realWeight;
    }

    public function getDatetime(): \DateTimeImmutable
    {
        return $this->date;
    }
}
