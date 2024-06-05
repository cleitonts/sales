<?php

declare(strict_types=1);

namespace App\Core\Domain\Model\Task;

use App\Shared\Domain\Model\RepositoryInterface;

interface TaskRepositoryInterface extends RepositoryInterface
{
    public function add(Task $task): void;

    public function remove(Task $task): void;
}
