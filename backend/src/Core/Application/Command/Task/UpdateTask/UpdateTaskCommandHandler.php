<?php

declare(strict_types=1);

namespace App\Core\Application\Command\Task\UpdateTask;

use App\Core\Domain\Model\Task\TaskRepositoryInterface;
use App\Core\Domain\Model\User\UserFetcherInterface;
use App\Shared\Domain\Exception\AccessForbiddenException;
use App\Shared\Domain\Exception\ResourceNotFoundException;

final class UpdateTaskCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private UserFetcherInterface $userFetcher
    ) {
    }

    public function __invoke(UpdateTaskCommand $command): void
    {
        $task = $this->taskRepository->find($command->getId());

        if (null === $task) {
            throw new ResourceNotFoundException(sprintf('Task with id "%s" is not found', $command->getId()));
        }

        $user = $this->userFetcher->fetchRequiredUser();

        if (!$task->getUser()->equals($user)) {
            throw new AccessForbiddenException('Access prohibited');
        }

        $task->changeTitle($command->getTitle());
        $task->changeDescription($command->getDescription());
        $task->changeExecutionDay($command->getExecutionDay());

        $this->taskRepository->add($task);
    }
}
