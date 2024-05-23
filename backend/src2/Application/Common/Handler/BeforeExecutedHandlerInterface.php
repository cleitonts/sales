<?php

namespace Panthir\Application\Common\Handler;

interface BeforeExecutedHandlerInterface
{
    public function beforeExecuted($model): void;
}
