<?php

namespace Panthir\Infrastructure\CommonBundle\Exception;

class InvalidFieldException extends \Exception implements CustomExceptionInterface
{
    public function __construct(
        protected $message = '',
        protected $code = 500,
        protected $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
