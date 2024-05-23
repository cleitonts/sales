<?php

namespace Panthir\Domain\User\Repository;

use Panthir\Application\Common\DTO\DTOInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

interface UserRepositoryInterface extends PasswordUpgraderInterface
{
    public function search(DTOInterface $DTO): array;
}
