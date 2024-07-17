<?php

/**
 * create auth
 * get protected data
 */
describe('Create auth token', function () {

    it('should create a auth token', function ($username, $password) {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/auth-token', [
            'username' => $username,
            'password' => $password,
        ]);
        $this->assertWrapper(fn() => $this->assertResponseStatusCodeSame(201));
    })->with([
        'admin' => ['admin', 'admin'],
    ]);
});