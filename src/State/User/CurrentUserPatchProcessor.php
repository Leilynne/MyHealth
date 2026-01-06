<?php

declare(strict_types=1);

namespace App\State\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\UserResource;
use App\Mapper\UserMapper;
use App\Repository\UserRepository;
use App\Security\SecurityHelperTrait;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @implements ProcessorInterface<UserResource>
 */
readonly class CurrentUserPatchProcessor implements ProcessorInterface
{
    use SecurityHelperTrait;

    public function __construct(
        private Security $security,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private UserMapper $userMapper,
    ) {
    }

    private function getSecurity(): Security
    {
        return $this->security;
    }

    /**'
     * @param UserResource $data
     * @throws \DateMalformedStringException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): UserResource
    {
        $user = $this->getUserStrict();
        $user->setName($data->name);
        $user->setHeight($data->height);
        $user->setTargetWeight($data->targetWeight);
        $user->setDateOfBirth(new \DateTimeImmutable($data->dateOfBirth));
        if (null !== $data->password) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $data->password));
        }
        $this->userRepository->save($user);

        return $this->userMapper->mapEntityToResource($user);
    }
}
