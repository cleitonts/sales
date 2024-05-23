<?php

namespace Panthir\Application\UseCase\Product;

use Panthir\Application\Common\Handler\AbstractHandler;
use Panthir\Domain\Product\Model\Brand;
use Ramsey\Uuid\Uuid;

class BrandCreateHandler extends AbstractHandler
{
    public function supports($model): bool
    {
        return !empty($model['name']);
    }

    /**
     * @param array $model
     */
    public function execute($model): Brand
    {
        $brand = (new Brand(Uuid::uuid4()))
            ->setName($model['name']);

        $this->entityManager->persist($brand);

        return $brand;
    }
}
