<?php

namespace Panthir\Application\UseCase\Customer\Normalizer\DTO;

class CustomerSearchDTO
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?int $limit = null,
        public readonly ?int $page = null,
    ) {
    }
}
