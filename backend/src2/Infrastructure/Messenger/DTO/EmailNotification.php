<?php

namespace Panthir\Infrastructure\Messenger\DTO;

class EmailNotification
{
    public function __construct(
        public readonly string $userEmail,
        public readonly string $resetPasswordtoken
    ) {
    }
}
