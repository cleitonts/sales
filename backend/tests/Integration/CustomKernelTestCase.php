<?php

namespace Tests\Integration;

use Faker\Generator;
use Panthir\Infrastructure\Faker\PortugalFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class CustomKernelTestCase extends KernelTestCase
{
    protected Generator $faker;

    protected function setUp(): void
    {
        $this->faker = PortugalFactory::build();
    }
}
