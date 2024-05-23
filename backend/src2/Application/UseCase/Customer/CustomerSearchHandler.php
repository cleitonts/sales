<?php

namespace Panthir\Application\UseCase\Customer;

use Panthir\Application\Common\Handler\AbstractHandler;
use Panthir\Application\UseCase\Customer\Normalizer\DTO\CustomerSearchDTO;
use Panthir\Domain\Customer\Model\Customer;

class CustomerSearchHandler extends AbstractHandler
{
    public function supports($object): bool
    {
        return $object instanceof CustomerSearchDTO;
    }

    /**
     * @param CustomerSearchDTO $model
     */
    public function execute($model): mixed
    {
        if ($model->id) {
            return $this->entityManager->getRepository(Customer::class)->find($model->id);
        }

        return $this->entityManager->getRepository(Customer::class)->search($model);
    }
}
