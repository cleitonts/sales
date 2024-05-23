<?php

namespace Tests\Application\User;

use Tests\Application\CustomApplicationAuthCase;

class PostTest extends CustomApplicationAuthCase
{
    public function testCreateSuccess(): void
    {
        static::$client->request('POST', '/api/user/', [
            'email' => 'teste1@teste.com',
        ]);

        $content = json_decode(static::$client->getResponse()->getContent(), true);
        $this->assertEmpty(array_diff(array_keys($content['data']), ['email', 'profile', 'id']));
        $this->assertResponseStatusCodeSame(200);
    }
}
