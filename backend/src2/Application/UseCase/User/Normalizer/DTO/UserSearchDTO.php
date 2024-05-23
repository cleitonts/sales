<?php

namespace Panthir\Application\UseCase\User\Normalizer\DTO;

class UserSearchDTO
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?string $token = null,
        public readonly ?int $limit = null,
        public readonly ?int $page = null,
        public readonly ?string $email = null,
        public readonly ?string $profile = null
    ) {
    }
}
