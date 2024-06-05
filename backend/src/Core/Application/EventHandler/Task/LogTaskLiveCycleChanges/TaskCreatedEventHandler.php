<?php

declare(strict_types=1);

namespace App\Core\Application\EventHandler\Task\LogTaskLiveCycleChanges;

use App\Core\Domain\Model\Task\TaskCreatedEvent;
use Psr\Log\LoggerInterface;

final class TaskCreatedEventHandler
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(TaskCreatedEvent $event): void
    {
        $this->logger->info(sprintf('Task %s was created', $event->getTask()->getId()));
    }
}
