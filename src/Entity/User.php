<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private string $email;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private int $height;

    #[ORM\Column]
    private int $targetWeight;

    #[ORM\Column]
    private \DateTimeImmutable $dateOfBirth;

    #[ORM\Column]
    private string $name;

    #[ORM\OneToMany(targetEntity: HeartParameters::class, mappedBy: 'user')]
    private Collection $heartParameters;

    #[ORM\OneToMany(targetEntity: WeightParameters::class, mappedBy: 'user')]
    private Collection $weightParameters;

    #[ORM\OneToMany(targetEntity: Exercise::class, mappedBy: 'user')]
    private Collection $exercise;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $apiToken = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): static
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    public function setTargetWeight(int $targetWeight): void
    {
        $this->targetWeight = $targetWeight;
    }

    public function setDateOfBirth(\DateTimeImmutable $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getTargetWeight(): int
    {
        return $this->targetWeight;
    }

    public function getDateOfBirth(): \DateTimeImmutable
    {
        return $this->dateOfBirth;
    }
}
