<?php

namespace tests\Application\User;

use Doctrine\ORM\EntityManagerInterface;
use Panthir\Domain\User\Model\User;
use Tests\Application\CustomApplicationAuthCase;

class PutTest extends CustomApplicationAuthCase
{
    public function testUpdatePasswordSuccess(): void
    {
        /** @var User $user */
        $user = static::getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository(User::class)
            ->findOneNotNull('passwordResetToken');

        static::$client->request('PUT', '/api/user/updatePassword', [
            'email' => $user->getEmail(),
            'passwordResetToken' => $user->getPasswordResetToken(),
            'password' => '123',
        ]);

        $content = json_decode(static::$client->getResponse()->getContent(), true);
        $this->assertEmpty(array_diff(array_keys($content['data']), ['email', 'profile', 'id']));
        $this->assertResponseStatusCodeSame(200);
    }
}
