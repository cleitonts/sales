<?php
use App\Core\Infrastructure\Repository\UserRepository;

/**
 * ADMIN
 *  - create user
 *  - update password
 *  - update data(username, email, ...)
 *  - set inactive
 *  - get user
 *  - get users list
 */

describe('Create user Action', function () {
    it('should create a user', function ($username, $password, $passwordRepeat) {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findBy(['username' => 'admin'])[0];
        $client->loginUser($testUser);

        $crawler = $client->request('POST', '/api/users', [
            'username' => $username,
            'password' => $password,
            'password_repeat' => $passwordRepeat
        ]);
        $this->assertWrapper(fn() => $this->assertResponseStatusCodeSame(201));
    })->with([
        'sasha' => ['sasha2', 'sasha', 'sasha'],
        'user' => ['user2', 'user12', 'user12'],
        'john' => ['john2', 'john12', 'john12']
    ]);
});