<?php

namespace Panthir\Application\UseCase\Product\Normalizer\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CategoryCreateDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $name,

        #[Assert\NotBlank]
        public readonly bool $isLastLevel,
        public readonly ?string $parentId = null,
    ) {
    }
}
