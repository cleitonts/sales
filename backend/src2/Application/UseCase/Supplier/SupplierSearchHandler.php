<?php

namespace Panthir\Application\UseCase\Supplier;

use Panthir\Application\Common\Handler\AbstractHandler;
use Panthir\Application\UseCase\Supplier\Normalizer\DTO\SupplierSearchDTO;
use Panthir\Domain\Supplier\Model\Supplier;

class SupplierSearchHandler extends AbstractHandler
{
    public function supports($object): bool
    {
        return $object instanceof SupplierSearchDTO;
    }

    /**
     * @param SupplierSearchDTO $model
     */
    public function execute($model): mixed
    {
        if ($model->id) {
            return $this->entityManager->getRepository(Supplier::class)->find($model->id);
        }

        return $this->entityManager->getRepository(Supplier::class)->search($model);
    }
}
