<?php

namespace Panthir\Infrastructure\Faker;

use Faker\Factory;
use Faker\Generator;
use Faker\Provider\pt_PT\Address;
use Faker\Provider\pt_PT\Payment;
use Faker\Provider\pt_PT\Person;

class PortugalFactory extends Factory
{
    public static function build(): Generator
    {
        $faker = parent::create('pt_PT');
        $faker->addProvider(new Person($faker));
        $faker->addProvider(new Payment($faker));
        $faker->addProvider(new Address($faker));

        return $faker;
    }
}
