<?php

namespace Panthir\Application\Services\Notify;

interface NotifyInterface
{
    public function addMessage($type, $text);

    public function newReturn($data);
}
