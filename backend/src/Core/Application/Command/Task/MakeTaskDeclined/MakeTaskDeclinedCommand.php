<?php

declare(strict_types=1);

namespace App\Core\Application\Command\Task\MakeTaskDeclined;

final class MakeTaskDeclinedCommand
{
    public function __construct(
        private int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
