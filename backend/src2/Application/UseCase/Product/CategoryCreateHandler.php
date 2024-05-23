<?php

namespace Panthir\Application\UseCase\Product;

use Panthir\Application\Common\Handler\AbstractHandler;
use Panthir\Application\UseCase\Product\Normalizer\DTO\CategoryCreateDTO;
use Panthir\Domain\Product\Model\Category;
use Ramsey\Uuid\Uuid;

class CategoryCreateHandler extends AbstractHandler
{
    public function supports($model): bool
    {
        return $model instanceof CategoryCreateDTO;
    }

    /**
     * @param CategoryCreateDTO $model
     */
    public function execute($model): Category
    {
        $parent = null;
        if (!empty($model->parentId)) {
            $parent = $this->entityManager->getRepository(Category::class)->find($model->parentId);
        }
        $category = (new Category(Uuid::uuid4()))
            ->setName($model->name)
            ->setIsLastLevel($model->isLastLevel)
            ->setParent($parent);

        $this->entityManager->persist($category);

        return $category;
    }
}
