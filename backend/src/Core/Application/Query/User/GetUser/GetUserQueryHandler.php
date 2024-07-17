<?php

declare(strict_types=1);

namespace App\Core\Application\Query\User\GetUser;

use App\Core\Application\Query\User\DTO\UserDTO;
use App\Core\Domain\Model\User\User;
use App\Core\Domain\Model\User\UserFetcherInterface;
use App\Shared\Domain\Exception\AccessForbiddenException;
use App\Shared\Domain\Exception\ResourceNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

final class GetUserQueryHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserFetcherInterface $userFetcher
    ) {
    }

    public function __invoke(GetUserQuery $query): UserDTO
    {
        $user = $this->em->find(User::class, $query->getId());

        if (null === $user) {
            throw new ResourceNotFoundException(sprintf('User with id "%s" is not found', $query->getId()));
        }

        $user = $this->userFetcher->fetchRequiredUser();

        if (!$user->getUser()->equals($user)) {
            throw new AccessForbiddenException('Access prohibited');
        }

        return UserDTO::fromEntity($user);
    }
}
