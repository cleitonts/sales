<?php

namespace Panthir\Application\UseCase\Product\Normalizer\DTO;

use Panthir\Domain\Product\Model\Brand;
use Symfony\Component\Validator\Constraints as Assert;

class ProductCreateDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $name,

        #[Assert\NotBlank]
        public readonly string $categoryId,

        #[Assert\NotBlank]
        public readonly float $value,

        #[Assert\NotBlank]
        public readonly ?Brand $brand = null,

        #[Assert\NotBlank]
        public readonly ?string $brandId = null,
    ) {
    }
}
