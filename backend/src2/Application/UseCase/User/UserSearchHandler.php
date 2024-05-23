<?php

namespace Panthir\Application\UseCase\User;

use Panthir\Application\Common\Handler\AbstractHandler;
use Panthir\Application\UseCase\User\Normalizer\DTO\UserSearchDTO;
use Panthir\Domain\User\Model\User;

class UserSearchHandler extends AbstractHandler
{
    public function supports($object): bool
    {
        return $object instanceof UserSearchDTO;
    }

    /**
     * @param UserSearchDTO $model
     */
    public function execute($model): mixed
    {
        if ($model->id) {
            $user = $this->entityManager->getRepository(User::class)->find($model->id);

            return $user;
        }
        $users = $this->entityManager->getRepository(User::class)->search($model);

        return $users;
    }
}
