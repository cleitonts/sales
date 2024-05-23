<?php

namespace Panthir\Application\Common\Transformer;

use Symfony\Component\HttpFoundation\Request;

interface TransformFromRequestInterface
{
    public static function transformFromRequest(Request $request): object;
}
