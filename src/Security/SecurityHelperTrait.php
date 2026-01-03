<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

trait SecurityHelperTrait
{
    abstract private function getSecurity(): Security;

    /**
     * @throws UnauthorizedHttpException
     */
    private function getUserStrict(): User
    {
        $user = $this->getSecurity()->getUser();

        if (false === $user instanceof User) {
            throw new UnauthorizedHttpException('Bearer');
        }

        return $user;
    }

    /**
     * @throws UnauthorizedHttpException
     */
    private function getUserSafe(): ?User
    {
        $user = $this->getSecurity()->getUser();

        if (null !== $user && false === $user instanceof User) {
            throw new UnauthorizedHttpException('Bearer');
        }

        return $user;
    }
}
