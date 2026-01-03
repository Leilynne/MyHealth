<?php

declare(strict_types=1);

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Enum\ArmEnum;
use App\State\HeartParameters\HeartParametersAddProcessor;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
//        new Get(
//            uriTemplate: '/current',
//            normalizationContext: ['groups' => [self::OUTPUT]],
//            provider: CurrentUserProvider::class
//        ),
        new Post(
            uriTemplate: '',
            normalizationContext: ['groups' => [self::OUTPUT]],
            denormalizationContext: ['groups' => [self::INPUT]],
            processor: HeartParametersAddProcessor::class,
        ),
    ],
    routePrefix: '/heart-parameters',
    exceptionToStatus: [
        UnauthorizedHttpException::class => 401,
    ],
)]
class HeartParametersResource
{
    private const string INPUT = 'Input';
    private const string OUTPUT = 'Output';

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Choice(callback: ArmEnum::class . '::stringCases')]
    #[Assert\NotBlank]
    public string $arm;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $heartbeat;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $systola;

    #[Groups([self::OUTPUT, self::INPUT])]
    #[Assert\Positive]
    #[Assert\NotNull]
    public int $diastola;

    #[Groups([self::OUTPUT])]
    public string $datetime;
}
