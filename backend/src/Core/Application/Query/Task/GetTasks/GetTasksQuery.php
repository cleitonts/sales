<?php

declare(strict_types=1);

namespace App\Core\Application\Query\Task\GetTasks;

use App\Shared\Infrastructure\ValueObject\Pagination;

final class GetTasksQuery
{
    public function __construct(
        private Pagination $pagination,
        private ?\DateTimeImmutable $executionDate = null,
        private ?string $searchText = null
    ){
    }

    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    public function getExecutionDate(): ?\DateTimeImmutable
    {
        return $this->executionDate;
    }

    public function getSearchText(): ?string
    {
        return $this->searchText;
    }
}
