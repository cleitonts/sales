<?php

namespace Panthir\Application\UseCase\Product\Normalizer\DTO;

class ProductSearchDTO
{
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?int $limit = null,
        public readonly ?int $page = null,
        public array $brands = []
    ) {
    }
}
