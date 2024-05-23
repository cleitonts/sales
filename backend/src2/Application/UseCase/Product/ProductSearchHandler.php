<?php

namespace Panthir\Application\UseCase\Product;

use Panthir\Application\Common\Handler\AbstractHandler;
use Panthir\Application\UseCase\Product\Normalizer\DTO\ProductSearchDTO;
use Panthir\Domain\Product\Model\Product;

class ProductSearchHandler extends AbstractHandler
{
    public function supports($object): bool
    {
        return $object instanceof ProductSearchDTO;
    }

    /**
     * @param ProductSearchDTO $model
     */
    public function execute($model): mixed
    {
        if ($model->id) {
            return $this->entityManager->getRepository(Product::class)->find($model->id);
        }

        return $this->entityManager->getRepository(Product::class)->search($model);
    }
}
