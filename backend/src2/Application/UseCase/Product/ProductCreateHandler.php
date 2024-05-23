<?php

namespace Panthir\Application\UseCase\Product;

use Panthir\Application\Common\Handler\AbstractHandler;
use Panthir\Application\UseCase\Product\Normalizer\DTO\ProductCreateDTO;
use Panthir\Domain\Product\Model\Category;
use Panthir\Domain\Product\Model\Product;
use Panthir\Infrastructure\CommonBundle\Exception\HandlerException;
use Ramsey\Uuid\Uuid;

class ProductCreateHandler extends AbstractHandler
{
    public function supports($object): bool
    {
        return $object instanceof ProductCreateDTO;
    }

    /**
     * @param ProductCreateDTO $model
     */
    public function execute($model): mixed
    {
        $category = $this->entityManager->getRepository(Category::class)->find($model->categoryId);

        $brand = $model->brand;
        if (empty($model->brand)) {
            $brand = $this->entityManager->getRepository(Category::class)->find($model->brandId);
        }
        if (empty($brand)) {
            throw new HandlerException('Invalid brand given', 410);
        }

        $product = (new Product(Uuid::uuid4()))
            ->setName($model->name)
            ->setBrand($model->brand)
            ->setValue($model->value)
            ->setCategory($category)
        ;

        $this->entityManager->persist($product);

        return $product;
    }
}
