<?php

namespace Tests\Application\Customer;

use Tests\Application\CustomApplicationAuthCase;

class GetTest extends CustomApplicationAuthCase
{
    public function testGetAllSuccess(): void
    {
        static::$client->request('GET', '/api/customer/');

        $content = json_decode(static::$client->getResponse()->getContent(), true);
        $this->assertNotEmpty($content['data']);
        $this->assertEmpty(array_diff(array_keys($content['data'][0]), ['name', 'surname', 'id', 'document', 'secondaryDocument', 'birthDate']));
        $this->assertResponseStatusCodeSame(200);
    }
}
