<?php

declare(strict_types=1);

namespace App\Core\Application\Command\Task\MakeTaskDone;

final class MakeTaskDoneCommand
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
