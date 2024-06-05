<?php

declare(strict_types=1);

namespace App\Core\Application\Command\Task\MakeTaskDeclined;

use App\Core\Domain\Model\Task\TaskRepositoryInterface;
use App\Core\Domain\Model\User\UserFetcherInterface;
use App\Shared\Domain\Exception\AccessForbiddenException;
use App\Shared\Domain\Exception\ResourceNotFoundException;

final class MakeTaskDeclinedCommandHandler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private UserFetcherInterface $userFetcher
    ){
    }

    public function __invoke(MakeTaskDeclinedCommand $command): void
    {
        $task = $this->taskRepository->find($command->getId());

        if (null === $task) {
            throw new ResourceNotFoundException(sprintf('Task with id "%s" is not found', $command->getId()));
        }

        $user = $this->userFetcher->fetchRequiredUser();

        if (!$task->getUser()->equals($user)) {
            throw new AccessForbiddenException('Access prohibited');
        }

        $task->decline();

        $this->taskRepository->add($task);
    }
}
