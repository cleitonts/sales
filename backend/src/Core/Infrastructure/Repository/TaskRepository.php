<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Application\Query\Task\GetTasks\GetTasksQuery;
use App\Core\Domain\Model\Task\Task;
use App\Core\Domain\Model\Task\TaskRepositoryInterface;
use App\Shared\Infrastructure\Type\DateTimeFormatEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository implements TaskRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function GetAll(GetTasksQuery $query, int $userId): QueryBuilder
    {
        $qb = $this->createQueryBuilder('t')
            ->innerJoin('t.user', 'u')
            ->orderBy('p.name', 'DESC')
            ->where('u.id = :userId')
            ->orderBy('t.createdAt')
            ->setFirstResult($query->getPagination()->getOffset())
            ->setMaxResults($query->getPagination()->getLimit())
            ->setParameter('userId', $userId);

        if (null !== $query->getExecutionDate()) {
            $executionDay = $query->getExecutionDate()->setTime(0, 0);
            $qb->andWhere('t.executionDay >= :fromTime')
                ->andWhere('t.executionDay < :toTime')
                ->setParameter('fromTime', $executionDay->format(DateTimeFormatEnum::DATETIME_FORMAT->value))
                ->setParameter('toTime', $executionDay->modify('+1 day')->format(DateTimeFormatEnum::DATETIME_FORMAT->value));
        }

        if (null !== $query->getSearchText()) {
            $qb->andWhere('t.title LIKE :searchText OR t.description LIKE :searchText')
                ->setParameter('searchText', "%{$query->getSearchText()}%");
        }

        return $qb;
    }

    public function add(Task $task): void
    {
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();
    }

    public function remove(Task $task): void
    {
        $this->getEntityManager()->remove($task);
        $this->getEntityManager()->flush();
    }
}
