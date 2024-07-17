<?php

namespace Tests;

use Symfony\Component\Dotenv\Dotenv;

uses(WebTestCase::class)
    ->beforeAll(function () {
        require $_SERVER['PWD'] . '/vendor/autoload.php';

        if (file_exists($_SERVER['PWD'] . '/config/bootstrap.php')) {
            require $_SERVER['PWD'] . '/config/bootstrap.php';
        } elseif (method_exists(Dotenv::class, 'bootEnv')) {
            (new Dotenv())->bootEnv($_SERVER['PWD'] . '/.env');
            (new Dotenv())->bootEnv($_SERVER['PWD'] . '/.env.test');
        }
    })
    ->in('*/Application/*');