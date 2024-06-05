<?php

declare(strict_types=1);

namespace App\Core\Application\Command\User\CreateUser;

use App\Core\Domain\Model\User\User;
use App\Core\Domain\Model\User\UserRepositoryInterface;
use App\Shared\Domain\Exception\InvalidInputDataException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CreateUserCommandHandler
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        if (!$this->userRepository->isSatisfiedBy($command->getUsername())) {
            throw new InvalidInputDataException(sprintf('Username %s already exists', $command->getUsername()));
        }

        $user = new User($command->getUsername());

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $command->getPassword()
        );

        $user->setPassword($hashedPassword);
        $this->userRepository->add($user);
    }
}
