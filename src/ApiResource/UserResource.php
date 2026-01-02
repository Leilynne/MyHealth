<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use App\State\CurrentUserPatchProcessor;
use App\State\CurrentUserProvider;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/current',
            normalizationContext: ['groups' => [self::OUTPUT]],
            provider: CurrentUserProvider::class
        ),
        new Patch(
            uriTemplate: '/edit',
            normalizationContext: ['groups' => [self::OUTPUT]],
            denormalizationContext: ['groups' => [self::INPUT]],
            provider: CurrentUserProvider::class,
            processor: CurrentUserPatchProcessor::class,
        ),
    ],
    routePrefix: '/user',
    exceptionToStatus: [
        UnauthorizedHttpException::class => 401,
    ],
)]
class UserResource
{
    private const string INPUT = 'Inout';
    private const string OUTPUT = 'Output';

    #[Groups([self::OUTPUT])]
    #[Assert\Email]
    public string $email;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public string $name;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    public int $height;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    public int $targetWeight;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Date]
    public string $dateOfBirth;

    #[Groups([self::INPUT])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    public string $password;
}
