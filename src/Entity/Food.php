<?php

namespace App\Entity;
use App\Repository\FoodRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FoodRepository::class)]
class Food
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $description;

    #[ORM\Column]
    private int $proteins;

    #[ORM\Column]
    private int $carbohydrates;

    #[ORM\Column]
    private int $fats;

    #[ORM\Column]
    private int $calories;

    #[ORM\Column]
    private bool $system = false;

    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: 'foodItems')]
    #[ORM\JoinColumn(nullable: false)]
    private Brand $brand;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'foodItems')]
    #[ORM\JoinColumn(nullable: false)]
    private Category $category;

    #[ORM\OneToMany(targetEntity: FoodIntake::class, mappedBy: 'food')]
    private Collection $intakes;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'foodItems')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setProteins(int $proteins): void
    {
        $this->proteins = $proteins;
    }

    public function setCarbohydrates(int $carbohydrates): void
    {
        $this->carbohydrates = $carbohydrates;
    }

    public function setFats(int $fats): void
    {
        $this->fats = $fats;
    }

    public function setCalories(int $calories): void
    {
        $this->calories = $calories;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function setBrand(Brand $brand): void
    {
        $this->brand = $brand;

    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function setSystem(bool $isSystem): void
    {
        $this->system = $isSystem;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getProteins(): int
    {
        return $this->proteins;
    }

    public function getCarbohydrates(): int
    {
        return $this->carbohydrates;
    }

    public function getFats(): int
    {
        return $this->fats;
    }

    public function getCalories(): int
    {
        return $this->calories;
    }

    public function isSystem(): bool
    {
        return $this->system;
    }

    public function getBrand(): Brand
    {
        return $this->brand;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }
}
