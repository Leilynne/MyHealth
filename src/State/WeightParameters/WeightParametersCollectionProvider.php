<?php

declare(strict_types=1);

namespace App\State\WeightParameters;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Filter\PeriodFilter;
use App\ApiResource\WeightParametersResource;
use App\Enum\PeriodEnum;
use App\Repository\WeightParametersRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<WeightParametersResource[]>
 */
readonly class WeightParametersCollectionProvider implements ProviderInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private WeightParametersRepository $weightParametersRepository,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    /**'
     * @return WeightParametersResource[]
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

        $weightParameters = $this->weightParametersRepository->findByUserAndSince($user->getId(), $since);

        $output = [];

        foreach ($weightParameters as $weightParameter) {
            $resource = new WeightParametersResource();

            $resource->weight = $weightParameter->getWeight();
            $resource->datetime = $weightParameter->getDatetime()->format('Y-m-d H:i:s');

            $output[] = $resource;
        }

        return $output;
    }
}
