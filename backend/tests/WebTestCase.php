<?php

namespace Tests;

use Pest\Support\Reflection;
use PHPUnit\Framework\ExpectationFailedException;
use \Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    public function assertWrapper(\Closure $fn)
    {
        try {
            $fn();
        } catch (ExpectationFailedException $e) {
            if($e->getPrevious() === null) {
                throw $e;
            }
            Reflection::setPropertyValue($e, 'message', $e->getPrevious()->getMessage());
            throw $e;
        }
    }
}