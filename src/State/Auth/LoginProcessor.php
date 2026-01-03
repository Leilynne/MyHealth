<?php

declare(strict_types=1);

namespace App\State\Auth;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\LoginResource;
use App\Repository\UserRepository;
use Random\RandomException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class LoginProcessor implements ProcessorInterface
{
    public function __construct(
        private UserRepository              $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    /**
     * @param LoginResource $data
     * @throws RandomException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): LoginResource
    {
        $user = $this->userRepository->findOneBy(['email' => $data->email]);

        if (null === $user || false === $this->passwordHasher->isPasswordValid($user, $data->password)) {
            throw new UnauthorizedHttpException('Bearer', 'Invalid credentials');
        }

        $token = bin2hex(random_bytes(32));
        $user->setApiToken($token);
        $this->userRepository->save($user);

        $output = new LoginResource();
        $output->token = $user->getApiToken();

        return $output;
    }
}
