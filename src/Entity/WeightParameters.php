<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeightParameters::class)]
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
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }
}
