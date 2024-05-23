<?php

namespace Tests\Application\User;

use Doctrine\ORM\EntityManagerInterface;
use Panthir\Domain\User\Model\User;
use Tests\Application\CustomApplicationAuthCase;

class GetTest extends CustomApplicationAuthCase
{
    public function testGetAllSuccess(): void
    {
        static::$client->request('GET', '/api/users/');

        $content = json_decode(static::$client->getResponse()->getContent(), true);
        $this->assertNotEmpty($content['data']);
        $this->assertEmpty(array_diff(array_keys($content['data'][0]), ['email', 'profile', 'id']));
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetByTokenSuccess(): void
    {
        /** @var User $user */
        $user = static::getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository(User::class)
            ->findOneNotNull('passwordResetToken');

        static::$client->request('GET', '/api/users/token/'.$user->getPasswordResetToken());

        $content = json_decode(static::$client->getResponse()->getContent(), true);
        $this->assertNotEmpty($content['data']);
        $this->assertEmpty(array_diff(array_keys($content['data'][0]), ['email', 'profile', 'id']));
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetProfileByUserSuccess(): void
    {
        static::$client->request('GET', '/api/users/profile');

        $content = json_decode(static::$client->getResponse()->getContent(), true);
        $this->assertNotEmpty($content['data']);
        $this->assertEmpty(array_diff(array_values($content['data']), ['PROFILE_VIEWER', 'PROFILE_WRITER', 'PROFILE_ACCOUNT_MANAGER']));
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetByIdError(): void
    {
        static::$client->request('GET', '/api/users/asd');

        $response = static::$client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertEquals('The given Id is invalid', $content['data']);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testGetByIdSuccess(): void
    {
        $user = static::getContainer()->get(EntityManagerInterface::class)->getRepository(User::class)->findOneBy([]);
        static::$client->request('GET', '/api/users/'.$user->getId());

        $response = static::$client->getResponse();
        $content = json_decode($response->getContent(), true);
        $this->assertNotEmpty($content['data']);
        $this->assertEmpty(array_diff(array_keys($content['data']), ['email', 'profile', 'id']));
        $this->assertResponseStatusCodeSame(200);
    }
}
