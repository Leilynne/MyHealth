<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\UserResource;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class CurrentUserPatchProcessor implements ProcessorInterface
{
    public function __construct(
        private Security $security,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    /**'
     * @param UserResource $data
     * @throws \DateMalformedStringException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): UserResource
    {
        $user = $this->security->getUser();

        if (false === $user instanceof User) {
            throw new UnauthorizedHttpException('Bearer');
        }
        $user->setName($data->name);
        $user->setHeight($data->height);
        $user->setTargetWeight($data->targetWeight);
        $user->setDateOfBirth(new \DateTimeImmutable($data->dateOfBirth));
        $user->setPassword($this->passwordHasher->hashPassword($user, $data->password));
        $this->userRepository->save($user);

        $output = new UserResource();
        $output->email = $user->getEmail();
        $output->name = $user->getName();
        $output->height = $user->getHeight();
        $output->targetWeight = $user->getTargetWeight();
        $output->dateOfBirth = $user->getDateOfBirth()->format('Y-m-d');

        return $output;
    }
}
