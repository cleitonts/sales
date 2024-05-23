<?php

namespace Tests\Application\Supplier;

use Doctrine\ORM\EntityManagerInterface;
use Panthir\Domain\Supplier\Model\Supplier;
use Tests\Application\CustomApplicationAuthCase;

class GetTest extends CustomApplicationAuthCase
{
    public function testGetAllSuccess(): void
    {
        static::$client->request('GET', '/api/supplier/');

        $content = json_decode(static::$client->getResponse()->getContent(), true);
        $this->assertNotEmpty($content['data']);
        $this->assertEmpty(array_diff(array_keys($content['data'][0]), ['name', 'nickName', 'id', 'document', 'secondaryDocument']));
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetByIdSuccess(): void
    {
        /** @var Supplier $supplier */
        $supplier = static::getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository(Supplier::class)
            ->findOneBy([]);

        static::$client->request('GET', '/api/supplier/'.$supplier->id);

        $content = json_decode(static::$client->getResponse()->getContent(), true);
        $this->assertNotEmpty($content['data']);

        $this->assertEmpty(array_diff(array_keys($content['data']), ['name', 'nickName', 'id', 'document', 'secondaryDocument', 'additionalInformation', 'addresses', 'contacts']));
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetByIdError(): void
    {
        static::$client->request('GET', '/api/supplier/asd');

        $response = static::$client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals('The given Id is invalid', $content['data']);
        $this->assertResponseStatusCodeSame(400);
    }
}
