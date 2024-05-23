<?php

namespace Panthir\Application\UseCase\User\Normalizer\DTO;

class RegisterDTO
{
    public function __construct(
        public readonly string $email,
        public readonly array $roles = [],

        public readonly ?string $password = null,
        public readonly ?string $passwordResetToken = null
    ) {
    }
}
