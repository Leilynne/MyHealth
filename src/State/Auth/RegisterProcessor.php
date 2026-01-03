<?php

declare(strict_types=1);

namespace App\State\Auth;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\RegisterResource;
use App\Entity\User;
use App\Repository\UserRepository;
use Random\RandomException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class RegisterProcessor implements ProcessorInterface
{
    public function __construct(
        private UserRepository              $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    /**
     * @param RegisterResource $data
     * @throws RandomException
     * @throws \DateMalformedStringException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): RegisterResource
    {
        $existingUser = $this->userRepository->findOneBy(['email' => $data->email]);
        if ($existingUser) {
            throw new UnprocessableEntityHttpException('User with this email already exists');
        }

        $user = new User();
        $user->setEmail($data->email);
        $user->setName($data->name);
        $user->setHeight($data->height);
        $user->setTargetWeight($data->targetWeight);
        $user->setDateOfBirth(new \DateTimeImmutable($data->dateOfBirth));

        $user->setPassword($this->passwordHasher->hashPassword($user, $data->password));
        $token = bin2hex(random_bytes(32));
        $user->setApiToken($token);

        $this->userRepository->save($user);

        $output = new RegisterResource();
        $output->token = $token;

        return $output;
    }
}
