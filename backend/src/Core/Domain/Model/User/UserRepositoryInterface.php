<?php

declare(strict_types=1);

namespace App\Core\Domain\Model\User;

use App\Shared\Domain\Model\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function findUserByUserName(string $username): ?User;

    public function add(User $user): void;

    public function remove(User $user): void;

    public function isSatisfiedBy(string $username): bool;
}
