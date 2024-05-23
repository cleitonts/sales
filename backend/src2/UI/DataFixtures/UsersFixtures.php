<?php

namespace Panthir\UI\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Panthir\Application\Common\Handler\HandlerRunner;
use Panthir\Application\UseCase\User\Normalizer\DTO\RegisterDTO;
use Panthir\Application\UseCase\User\UserCreateHandler;
use Panthir\Domain\User\ValueObject\UserRoles;

class UsersFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private readonly HandlerRunner $handlerRunner,
        private readonly UserCreateHandler $userCreateHandler
    ) {
    }

    public static function getGroups(): array
    {
        return ['user'];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; ++$i) {
            $faker = Factory::create();
            $roles = constant(UserRoles::class.'::'.UserRoles::LIST_PROFILES[$faker->numberBetween(0, count(UserRoles::LIST_PROFILES) - 1)]);

            $this->handlerRunner->__invoke($this->userCreateHandler,
                new RegisterDTO(
                    email: $faker->email(),
                    roles: $roles,
                )
            );
        }

        $manager->flush();
    }
}
