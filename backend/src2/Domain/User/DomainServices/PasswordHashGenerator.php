<?php

namespace Panthir\Domain\User\DomainServices;

use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;

class PasswordHashGenerator
{
    public function __invoke(string $plainPassword): string
    {
        // Configure different password hashers via the factory
        $factory = new PasswordHasherFactory([
            'common' => ['algorithm' => 'bcrypt'],
            'memory-hard' => ['algorithm' => 'sodium'],
        ]);

        // Retrieve the right password hasher by its name
        $passwordHasher = $factory->getPasswordHasher('common');

        // Hash a plain password
        return $passwordHasher->hash($plainPassword);
    }
}
