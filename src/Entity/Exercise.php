<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: Exercise::class)]
class Exercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private int $duration;

    #[ORM\Column]
    private \DateTimeImmutable $date;

    #[ORM\Column]
    private bool $default;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'exercise')]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }
}
