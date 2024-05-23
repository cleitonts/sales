<?php

namespace Panthir\Application\Common\Handler;

interface CommonHandlerInterface
{
    public function execute($model): mixed;

    public function supports($model): bool;
}
