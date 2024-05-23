<?php

namespace Panthir\Application\Common\Handler;

interface AfterExecutedHandlerInterface
{
    public function afterExecuted($model): void;
}
