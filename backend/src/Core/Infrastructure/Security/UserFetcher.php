<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Security;

use App\Core\Domain\Model\User\User;
use App\Core\Domain\Model\User\UserFetcherInterface;
use Symfony\Component\Security\Core\Security;

final class UserFetcher implements UserFetcherInterface
{
    public function __construct(
        private Security $security
    ) {
    }

    public function fetchRequiredUser(): User
    {
        $user = $this->security->getUser();

        if (null === $user) {
            throw new \InvalidArgumentException('Current user not found check security access list');
        }

        if (!($user instanceof User)) {
            throw new \InvalidArgumentException(sprintf('Invalid user type %s', \get_class($user)));
        }

        return $user;
    }

    public function fetchNullableUser(): ?User
    {
        $user = $this->security->getUser();

        if (null === $user) {
            return null;
        }

        if (!($user instanceof User)) {
            throw new \InvalidArgumentException(sprintf('Invalid user type %s', \get_class($user)));
        }

        return $user;
    }
}
