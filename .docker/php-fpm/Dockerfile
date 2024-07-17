FROM php:8.3.9-fpm-alpine3.20

RUN echo "------> ENV ${ENV}"

RUN apk update && apk add \
    openssl \
    git \
    unzip \
    curl \
    autoconf \
    gcc \
    g++ \
    make \
    linux-headers \
    libzip-dev \
    postgresql-dev \
    oniguruma-dev \
    bash \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

RUN docker-php-ext-install \
   pdo pdo_pgsql zip intl opcache mbstring

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

WORKDIR /var/www
COPY ./backend /var/www
RUN chmod 755 -R /var/www

RUN chown -R www-data:www-data /var/www
#USER www-data
#RUN composer install --no-suggest --quiet
#RUN php bin/console lexik:jwt:generate-keypair --skip-if-exists
#RUN php bin/console doctrine:database:create --if-not-exists
#RUN php bin/console --no-interaction doctrine:migrations:migrate
USER root
RUN chmod -R 775 /var/www/var

# Xdebug
ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} ]; \
    then \
      pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug; \
    fi;
COPY ./.docker/php-fpm/xdebug.ini "${PHP_INI_DIR}/conf.d"
COPY ./.docker/php-fpm/error_reporting.ini "${PHP_INI_DIR}/conf.d"

CMD ["php-fpm"]
