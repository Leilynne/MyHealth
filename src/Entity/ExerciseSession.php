<?php

namespace App\Entity;
use App\Repository\ExerciseSessionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciseSessionRepository::class)]
class ExerciseSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private int $duration;

    #[ORM\Column]
    private \DateTimeImmutable $performedAt;

    #[ORM\ManyToOne(targetEntity: Exercise::class, inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: false)]
    private Exercise $exercise;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'exerciseSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function setExercise(Exercise $exercise): void
    {
        $this->exercise = $exercise;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setPerformedAt(\DateTimeImmutable $datetime): void
    {
        $this->performedAt = $datetime;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function getExercise(): Exercise
    {
        return $this->exercise;
    }

    public function getPerformedAt(): \DateTimeImmutable
    {
        return $this->performedAt;
    }

    public function getTotalKcal(): float
    {
        return $this->exercise->getKcalPerHour() * $this->getDuration() / 60;
    }
}
