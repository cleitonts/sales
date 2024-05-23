<?php

namespace Panthir\Application\UseCase\User\Normalizer\DTO;

class PasswordUpdateDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $passwordResetToken
    ) {
    }
}
