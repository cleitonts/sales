#!/bin/bash
cd /var/www
echo "============> Iniciando instalação"

declare -A REP

echo "------> Criando .env.$ENV"
touch /var/www/.env.$ENV
echo "DATABASE_URL='pgsql://$DB_USER:$DB_PASS@$DB_HOST:$DB_PORT/$DB_NAME'" > /var/www/.env.$ENV
echo "APP_ENV=$ENV" >> /var/www/.env.$ENV

echo "------> Composer install"

if [ "$ENV" = "prod" ]
then
  echo "------> ENV $ENV"
#	composer install --no-dev --optimize-autoloader
  composer install --no-suggest --quiet
  composer dump-env prod
else
	composer install --no-suggest --quiet
fi

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
