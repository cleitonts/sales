<?php

declare(strict_types=1);

namespace App\Core\Application\Command\User\CreateUser;

use App\Core\Domain\Factory\User\UserFactory;
use App\Core\Domain\Model\User\UserRepositoryInterface;
use App\Shared\Domain\Exception\InvalidInputDataException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CreateUserCommandHandler
{
    const int MIN_PASSWORD_LENGTH = 5;

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepositoryInterface     $userRepository,
        private readonly UserFactory                 $userFactory
    )
    {
    }

    /** @throws InvalidInputDataException */
    public function __invoke(CreateUserCommand $command): string
    {
        if ('' === $command->getUsername()) {
            throw new InvalidInputDataException('User name should be not blank');
        }

        if (\strlen($command->getPassword()) < self::MIN_PASSWORD_LENGTH) {
            throw new InvalidInputDataException('Password is to short, need more than 4 symbols (bytes)');
        }

        if ($command->getPassword() !== $command->getPasswordRepeat()) {
            throw new InvalidInputDataException("Passwords doesn't match");
        }

        if (!$this->userRepository->isSatisfiedBy($command->getUsername())) {
            throw new InvalidInputDataException(sprintf('Username %s already exists', $command->getUsername()));
        }

        $user = $this->userFactory->create($command->getUsername());
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $command->getPassword()
        );

        $user->setPassword($hashedPassword);
        $this->userRepository->add($user);
        return $user->getId();
    }
}
