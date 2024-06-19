# 🐳 Docker + PHP 8 + Nginx + Symfony 6 (DDD, Messenger) + PostgreSQL

## Description

This is a complete stack for running Symfony 6 into Docker containers using docker-compose tool.

It is composed by 3 services:

- `nginx`, acting as the webserver.
- `php-fpm`, the PHP-FPM container with the PHP 8.1 version.
- `postgres` which is the MySQL database container with a **PostgreSQL 15.1** image.

## Installation

1. 😀 Clone this rep.

2. Run `docker-compose up -d`

## Usage
If everything went well, you can check the api in: 
http://localhost:8080/api/doc

## Tools

you can run php code quality tools like phpcs, phpstan and deptrac 
bashing into php-fpm container and running:

* `php ./vendor/bin/phpstan analyse ./src/`
* `php ./vendor/bin/deptrac`
* `php ./vendor/bin/php-cs-fixer fix`
