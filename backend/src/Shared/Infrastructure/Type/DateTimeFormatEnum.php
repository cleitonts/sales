<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Type;

enum DateTimeFormatEnum: string
{
    case DATE_FORMAT = 'Y-m-d';
    case DATETIME_FORMAT = 'Y-m-d H:i:s';
}
