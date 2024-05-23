<?php

namespace Tests\Application;

use Panthir\Infrastructure\Repository\User\UserRepository;

abstract class CustomApplicationAuthCase extends CustomApplicationCase
{
    protected function setUp(): void
    {
        parent::setUp();

        self::$client->setServerParameter('CONTENT_TYPE', 'application/json');
        self::$client->setServerParameter('HTTP_ACCEPT', 'application/json');
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('john@doe.com');
        self::$client->loginUser($testUser);
    }
}
