<?php

namespace Panthir\Application\UseCase\Product;

use Panthir\Application\Common\Handler\AbstractHandler;
use Panthir\Application\UseCase\Product\Normalizer\DTO\ProductSearchDTO;
use Panthir\Domain\Product\Model\Category;

class CategorySearchHandler extends AbstractHandler
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
        return $this->entityManager->getRepository(Category::class)->getTree();
    }
}
