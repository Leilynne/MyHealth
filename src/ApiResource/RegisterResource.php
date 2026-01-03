<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\State\Auth\RegisterProcessor;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/register',
            normalizationContext: ['groups' => [self::OUTPUT]],
            denormalizationContext: ['groups' => [self::INPUT]],
            processor: RegisterProcessor::class
        ),
    ],
)]
class RegisterResource
{
    private const string INPUT = 'Input';
    private const string OUTPUT = 'Output';

    #[Groups([self::INPUT])]
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Groups([self::INPUT])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    public string $password;

    #[Groups([self::INPUT])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public string $name;

    #[Groups([self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $height;

    #[Groups([self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $targetWeight;

    #[Groups([self::INPUT])]
    #[Assert\Date]
    #[Assert\NotNull]
    public string $dateOfBirth;

    #[Groups([self::OUTPUT])]
    public string $token;
}
