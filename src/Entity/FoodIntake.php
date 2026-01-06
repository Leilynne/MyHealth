<?php

namespace App\Entity;
use App\Enum\FoodIntakeTypeEnum;
use App\Repository\FoodIntakeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FoodIntakeRepository::class)]
class FoodIntake
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $date;

    #[ORM\Column]
    private int $amount;

    #[ORM\Column]
    private FoodIntakeTypeEnum $type;

    #[ORM\ManyToOne(targetEntity: Food::class, inversedBy: 'intakes')]
    #[ORM\JoinColumn(nullable: false)]
    private Food $food;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'foodIntakes')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setFood(Food $food): void
    {
        $this->food = $food;
    }

    public function getFood(): Food
    {
        return $this->food;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function setType(FoodIntakeTypeEnum $type): void
    {
        $this->type = $type;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getType(): FoodIntakeTypeEnum
    {
        return $this->type;
    }
}
