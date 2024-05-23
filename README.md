# 🐳 Docker + PHP 8 + PostgreSQL + Vue 3 + Typescript + Nginx + Symfony 6 (Messenger, mailer) + Redis

## Description

This is a complete stack for running Symfony 6 into Docker containers using docker-compose tool.

It is composed by 3 services:

- `nginx`, acting as the webserver.
- `php-fpm`, the PHP-FPM container with the PHP 8.1 version.
- `postgres` which is the MySQL database container with a **PostgreSQL 15.1** image.

## Installation

1. 😀 Clone this rep.

2. Run `docker-compose up -d`
