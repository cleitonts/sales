<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http;

enum HttpSpecEnum: string
{
    case STR_HTTP_OK = 'OK';
    case STR_HTTP_BAD_REQUEST = 'Bad request';
    case STR_HTTP_UNAUTHORIZED = 'Unauthorized';
    case STR_HTTP_NOT_FOUND = 'Not found';
    case STR_HTTP_CREATED = 'Created';
    case STR_HTTP_NO_CONTENT = 'No content';

    case HEADER_X_ITEMS_COUNT = 'X-Items-Count';
    case HEADER_LOCATION = 'Location';
}
