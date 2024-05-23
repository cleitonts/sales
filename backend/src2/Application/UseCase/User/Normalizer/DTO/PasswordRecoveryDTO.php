<?php

namespace Panthir\Application\UseCase\User\Normalizer\DTO;

class PasswordRecoveryDTO
{
    public function __construct(
        public readonly string $email,
        public readonly ?string $password = null,
        public readonly ?string $passwordResetToken = null
    ) {
    }
}
