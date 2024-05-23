<?php

declare(strict_types=1);

namespace App\Core\Application\Command\AuthToken\CreateAuthToken;

use App\Core\Domain\Model\User\UserRepositoryInterface;
use App\Shared\Domain\Exception\InvalidInputDataException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CreateAuthTokenCommandHandler
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordEncoder,
        private readonly UserRepositoryInterface     $userRepository,
        private readonly JWTTokenManagerInterface $JWTTokenManager
    ) {
    }

    public function __invoke(CreateAuthTokenCommand $command): string
    {
        $user = $this->userRepository->findUserByUserName($command->getUsername());

        if (null === $user) {
            throw new InvalidInputDataException('Invalid credentials');
        }

        if (!$this->userPasswordEncoder->isPasswordValid($user, $command->getPassword())) {
            throw new InvalidInputDataException('Invalid credentials');
        }

        return $this->JWTTokenManager->create($user);
    }
}
