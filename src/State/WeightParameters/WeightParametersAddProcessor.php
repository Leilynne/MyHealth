<?php

declare(strict_types=1);

namespace App\State\WeightParameters;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\WeightParametersResource;
use App\Entity\WeightParameters;
use App\Mapper\WeightParametersMapper;
use App\Repository\WeightParametersRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<WeightParametersResource>
 */
readonly class WeightParametersAddProcessor implements ProcessorInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private WeightParametersRepository $weightParametersRepository,
        private WeightParametersMapper $weightParametersMapper,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    /**'
     * @param WeightParametersResource $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): WeightParametersResource
    {
        $user = $this->getUserStrict();
        $datetime = new \DateTimeImmutable();
        $weightParameters = new WeightParameters();
        $weightParameters->setWeight($data->weight);
        $weightParameters->setDatetime($datetime);
        $weightParameters->setUser($user);
        $this->weightParametersRepository->save($weightParameters);

        return $this->weightParametersMapper->mapEntityToResource($weightParameters);
    }
}
