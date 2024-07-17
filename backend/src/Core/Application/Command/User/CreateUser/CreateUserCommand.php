<?php

declare(strict_types=1);

namespace App\Core\Application\Command\User\CreateUser;

final readonly class CreateUserCommand
{
    public function __construct(
        private string $username,
        private string $password,
        private string $passwordRepeat
    )
    {
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPasswordRepeat(): string
    {
        return $this->passwordRepeat;
    }
}
