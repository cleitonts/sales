#!/bin/bash

echo "------> Composer install"
cd /var/www

composer install --no-suggest --quiet

echo "------> Key gen"
php bin/console lexik:jwt:generate-keypair --skip-if-exists

echo "------> Doctrine migrations"
php bin/console doctrine:database:create --if-not-exists
php bin/console --no-interaction doctrine:migrations:migrate

chmod -R 777 /var/www/var
echo "------> Done"

php-fpm -R
