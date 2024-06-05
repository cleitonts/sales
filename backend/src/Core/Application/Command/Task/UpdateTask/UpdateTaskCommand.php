<?php

declare(strict_types=1);

namespace App\Core\Application\Command\Task\UpdateTask;

use App\Core\Application\Command\Task\TaskCommand;

final class UpdateTaskCommand extends TaskCommand
{
    public function __construct(
        private int $id,
        string $title,
        \DateTimeImmutable
        $executionDay,
        string $description = ''
    ){
        parent::__construct($title, $executionDay, $description);
    }

    public function getId(): int
    {
        return $this->id;
    }
}
