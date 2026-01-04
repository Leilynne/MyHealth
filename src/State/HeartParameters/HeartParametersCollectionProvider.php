<?php

declare(strict_types=1);

namespace App\State\HeartParameters;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Filter\PeriodFilter;
use App\ApiResource\HeartParametersResource;
use App\Enum\PeriodEnum;
use App\Repository\HeartParametersRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<HeartParametersResource[]>
 */
readonly class HeartParametersCollectionProvider implements ProviderInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private HeartParametersRepository $heartParametersRepository,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    /**'
     * @return HeartParametersResource[]
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $user = $this->getUserStrict();

        $periodValue = $context['filters'][PeriodFilter::NAME] ?? '';
        $since = match (PeriodEnum::tryFrom($periodValue)) {
            null => null,
            PeriodEnum::Day => new \DateTimeImmutable('-1 day'),
            PeriodEnum::Week => new \DateTimeImmutable('-1 week'),
            PeriodEnum::Month => new \DateTimeImmutable('-1 month'),
        };

        $heartParameters = $this->heartParametersRepository->findByUserAndSince($user->getId(), $since);

        $output = [];

        foreach ($heartParameters as $heartParameter) {
            $resource = new HeartParametersResource();

            $resource->arm = $heartParameter->getArm()->value;
            $resource->systola = $heartParameter->getSystola();
            $resource->diastola = $heartParameter->getDiastola();
            $resource->heartbeat = $heartParameter->getHeartBeat();
            $resource->datetime = $heartParameter->getDatetime()->format('Y-m-d H:i:s');

            $output[] = $resource;
        }

        return $output;
    }
}
