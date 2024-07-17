<?php

declare(strict_types=1);

namespace App\Core\Application\Query\User\GetUser;

final class GetUserQuery
{
    public function __construct(
        private string $id
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }
}
