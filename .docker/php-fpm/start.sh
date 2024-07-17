#!/bin/bash
cd /var/www
echo "============> Iniciando instalação"

echo "------> Composer install"
composer install --no-suggest --quiet

chmod a+rw -R /var/www

echo "------> Key gen"
php bin/console lexik:jwt:generate-keypair --skip-if-exists

echo "============> Instalação concluída"

echo "------> Doctrine migrations"
php bin/console doctrine:database:create --if-not-exists
php bin/console --no-interaction doctrine:migrations:migrate

chmod -R 775 /var/www/var
chown -R www-data:www-data /var/www/

php-fpm -R
