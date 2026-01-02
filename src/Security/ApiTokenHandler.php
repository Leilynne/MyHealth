<?php

declare(strict_types=1);

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class ApiTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $user = $this->userRepository->findOneBy(['apiToken' => $accessToken]);

        if (null === $user) {
            throw new BadCredentialsException('Invalid access token.');
        }

        return new UserBadge($user->getUserIdentifier());
    }
}
