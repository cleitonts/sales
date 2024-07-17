<?php

use App\Core\Application\Command\User\CreateUser\CreateUserCommand;
use App\Core\Application\Command\User\CreateUser\CreateUserCommandHandler;
use App\Core\Domain\Factory\User\UserFactory;
use App\Core\Domain\Model\User\User;
use App\Core\Domain\Model\User\UserRepositoryInterface;
use App\Shared\Domain\Exception\InvalidInputDataException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

describe('CreateUserCommandHandler', function () {
    it('should create a user', function () {
        $userRepository = mock(UserRepositoryInterface::class);
        $hasher = mock(UserPasswordHasherInterface::class);
        $userFactory = mock(UserFactory::class);

        $user = new User('admin', 'admin');

        $userRepository->shouldReceive('isSatisfiedBy')
            ->with('admin')
            ->once()
            ->andReturnTrue();

        $userFactory->shouldReceive('create')
            ->with('admin')
            ->once()
            ->andReturn($user);

        $hasher->shouldReceive('hashPassword')
            ->with($user, 'admin')
            ->once()
            ->andReturn('hashedPassword');

        $userRepository->shouldReceive('add')
            ->with($user)
            ->once();

        $handler = new CreateUserCommandHandler($hasher, $userRepository, $userFactory);
        $command = new CreateUserCommand(username: 'admin', password: 'admin', passwordRepeat: 'admin');

        $handler($command);
    });

    it('should throw an exception if the username is empty', function () {
        $userRepository = mock(UserRepositoryInterface::class);
        $passwordHasher = mock(UserPasswordHasherInterface::class);
        $userFactory = mock(UserFactory::class);
        $handler = new CreateUserCommandHandler($passwordHasher, $userRepository, $userFactory);

        $command = new CreateUserCommand(username: '', password: 'admin', passwordRepeat: 'admin');
        expect(fn() => $handler($command))->toThrow(new InvalidInputDataException('User name should be not blank'));
    });

    it('should throw an exception if the password is too short', function () {
        $userRepository = mock(UserRepositoryInterface::class);
        $passwordHasher = mock(UserPasswordHasherInterface::class);
        $userFactory = mock(UserFactory::class);
        $handler = new CreateUserCommandHandler($passwordHasher, $userRepository, $userFactory);

        $command = new CreateUserCommand(username: 'admin', password: '1234', passwordRepeat: '1234');
        expect(fn() => $handler($command))->toThrow(new InvalidInputDataException('Password is to short, need more than 4 symbols (bytes)'));
    });

    it('should throw an exception if the passwords do not match', function () {
        $userRepository = mock(UserRepositoryInterface::class);
        $passwordHasher = mock(UserPasswordHasherInterface::class);
        $userFactory = mock(UserFactory::class);
        $handler = new CreateUserCommandHandler($passwordHasher, $userRepository, $userFactory);

        $command = new CreateUserCommand(username: 'admin', password: 'admin', passwordRepeat: 'password');
        expect(fn() => $handler($command))->toThrow(new InvalidInputDataException("Passwords doesn't match"));
    });

    it('should throw an exception if the username already exists', function () {
        $userRepository = mock(UserRepositoryInterface::class);
        $passwordHasher = mock(UserPasswordHasherInterface::class);
        $userFactory = mock(UserFactory::class);
        $handler = new CreateUserCommandHandler($passwordHasher, $userRepository, $userFactory);

        $command = new CreateUserCommand(username: 'admin', password: 'admin', passwordRepeat: 'admin');
        $userRepository->shouldReceive('isSatisfiedBy')->with('admin')->once()->andReturnfalse();
        expect(fn() => $handler($command))->toThrow(new InvalidInputDataException('Username admin already exists'));
    });

});