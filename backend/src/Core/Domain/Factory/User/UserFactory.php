<?php

namespace App\Core\Domain\Factory\User;

use App\Core\Domain\Model\User\User;
use Symfony\Component\Uid\Uuid;

class UserFactory
{
    public function create(string $username): User
    {
        return (new User(Uuid::v4(), $username));
    }
}