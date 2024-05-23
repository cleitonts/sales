<?php

namespace Panthir\Application\UseCase\Supplier\Normalizer\DTO;

class SupplierSearchDTO
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?int $limit = null,
        public readonly ?int $page = null,
    ) {
    }
}
