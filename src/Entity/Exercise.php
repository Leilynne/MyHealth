<?php

namespace App\Entity;
use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
class Exercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private string $description;

    #[ORM\Column]
    private int $kcalPerHour;

    #[ORM\Column]
    private bool $system = false;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'exercises')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: ExerciseSession::class, mappedBy: 'exercise')]
    private Collection $sessions;

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setKcalPerHour(int $kcalPerHour): void
    {
        $this->kcalPerHour = $kcalPerHour;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getKcalPerHour(): int
    {
        return $this->kcalPerHour;
    }

    public function isSystem(): bool
    {
        return $this->system;
    }

    public function setSystem(bool $isSystem): void
    {
        $this->system = $isSystem;
    }
}
