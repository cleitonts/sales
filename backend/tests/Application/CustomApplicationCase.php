<?php

namespace Tests\Application;

use Faker\Generator;
use Panthir\Infrastructure\Faker\PortugalFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class CustomApplicationCase extends WebTestCase
{
    protected static ?KernelBrowser $client = null;
    protected Generator $faker;

    protected function setUp(): void
    {
        $this->faker = PortugalFactory::build();
        self::$client = static::createClient();
        self::$client->enableProfiler();
    }
}
