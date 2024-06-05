<?php

declare(strict_types=1);

namespace App\Core\Application\Query\Task\GetTask;

final class GetTaskQuery
{
    public function __construct(
        private int $id
    ){
    }

    public function getId(): int
    {
        return $this->id;
    }
}
