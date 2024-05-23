<?php

namespace Panthir\Application\UseCase\Product;

use Panthir\Application\Common\Handler\AbstractHandler;
use Panthir\Application\UseCase\Product\Normalizer\DTO\ProductSearchDTO;
use Panthir\Domain\Product\Model\Brand;

class ProductGetBrandsHandler extends AbstractHandler
{
    public function supports($object): bool
    {
        return is_array($object);
    }

    /**
     * @param ProductSearchDTO $model
     */
    public function execute($model): mixed
    {
        return $this->entityManager->getRepository(Brand::class)->findAll();
    }
}
