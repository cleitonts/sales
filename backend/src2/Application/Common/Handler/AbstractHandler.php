<?php

namespace Panthir\Application\Common\Handler;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractHandler implements CommonHandlerInterface
{
    public function __construct(
        protected readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
