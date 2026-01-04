<?php

declare(strict_types=1);

namespace App\State\HeartParameters;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\HeartParametersResource;
use App\Entity\HeartParameters;
use App\Enum\ArmEnum;
use App\Repository\HeartParametersRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<HeartParametersResource>
 */
readonly class HeartParametersAddProcessor implements ProcessorInterface
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
     * @param HeartParametersResource $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): HeartParametersResource
    {
        $user = $this->getUserStrict();
        $datetime = new \DateTimeImmutable();
        $heartParameters = new HeartParameters();
        $heartParameters->setArm(ArmEnum::from($data->arm));
        $heartParameters->setHeartBeat($data->heartbeat);
        $heartParameters->setSystola($data->systola);
        $heartParameters->setDiastola($data->diastola);
        $heartParameters->setDatetime($datetime);
        $heartParameters->setUser($user);
        $this->heartParametersRepository->save($heartParameters);

        $output = new HeartParametersResource();
        $output->arm = $heartParameters->getArm()->value;
        $output->heartbeat = $heartParameters->getHeartBeat();
        $output->systola = $heartParameters->getSystola();
        $output->diastola = $heartParameters->getDiastola();
        $output->datetime = $heartParameters->getDatetime()->format('Y-m-d H:i:s');

        return $output;
    }
}
