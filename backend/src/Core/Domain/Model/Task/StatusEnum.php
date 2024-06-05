<?php

declare(strict_types=1);

namespace App\Core\Domain\Model\Task;

enum StatusEnum: string
{
    case NEW = 'new';
    case DECLINED = 'declined';
    case DONE = 'done';
}
