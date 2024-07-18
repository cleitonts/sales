FROM php:8.3.9-fpm-alpine3.20

ARG UID=1000
ARG GID=1000

RUN addgroup -g "${GID}" php-admin \
    && adduser -u "${UID}" -G php-admin -h /home/php-admin -D -s /bin/sh php-admin \
    && addgroup php-admin root

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

# Xdebug
ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} ]; \
    then \
      pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug; \
    fi;
COPY ./.docker/php-fpm/xdebug.ini "${PHP_INI_DIR}/conf.d"
COPY ./.docker/php-fpm/error_reporting.ini "${PHP_INI_DIR}/conf.d"

COPY ./.docker/php-fpm/start.sh "/usr/local/bin/start.sh"
RUN chmod +x "/usr/local/bin/start.sh"

WORKDIR /var/www
COPY ./backend /var/www

RUN chown -R php-admin:php-admin /var/www \
    && chmod 755 -R /var/www

USER php-admin

ENTRYPOINT ["/usr/local/bin/start.sh"]
CMD ["php-fpm"]
