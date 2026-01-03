<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\State\Auth\LoginProcessor;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/login',
            normalizationContext: ['groups' => [self::OUTPUT]],
            denormalizationContext: ['groups' => [self::INPUT]],
            processor: LoginProcessor::class
        ),
    ],
    exceptionToStatus: [
        UnauthorizedHttpException::class => 401,
    ]
)]
class LoginResource
{
    private const string INPUT = 'Input';
    private const string OUTPUT = 'Output';

    #[Groups([self::INPUT])]
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Groups([self::INPUT])]
    #[Assert\NotBlank]
    public string $password;

    #[Groups([self::OUTPUT])]
    public string $token;
}
