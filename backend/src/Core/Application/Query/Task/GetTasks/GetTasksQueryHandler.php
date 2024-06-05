<?php

declare(strict_types=1);

namespace App\Core\Application\Query\Task\GetTasks;

use App\Core\Application\Query\Task\DTO\TaskDTO;
use App\Core\Domain\Model\User\UserFetcherInterface;
use App\Core\Infrastructure\Repository\TaskRepository;
use App\Shared\Infrastructure\Doctrine\CountableTrait;
use App\Shared\Infrastructure\ValueObject\PaginatedData;
use Doctrine\ORM\EntityManagerInterface;

final class GetTasksQueryHandler
{
    use CountableTrait;

    public function __construct(
        private EntityManagerInterface $em,
        private UserFetcherInterface $userFetcher,
        private TaskRepository $taskRepository
    ) {
    }

    public function __invoke(GetTasksQuery $query): PaginatedData
    {
        $userId = $this->userFetcher->fetchRequiredUser()->getId();

        $qb = $this->taskRepository->GetAll($query, $userId);
        //        $tasks = $this->em->getConnection()->executeQuery($qb->getSQL(), $qb->getParameters())->fetchAllAssociative();

        $taskDTOs = [];

        foreach ($qb->getQuery()->getArrayResult() as $task) {
            $taskDTOs[] = TaskDTO::fromQueryArray($task);
        }

        $total = $this->countAllResults($qb);

        return new PaginatedData($taskDTOs, $total);
    }
}
