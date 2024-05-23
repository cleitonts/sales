FROM php:8.3.4-fpm-bullseye

ARG COMPOSE_PROJECT_NAME
ARG INSTALL_XDEBUG
ARG DB_PORT
ARG DB_HOST
ARG DB_USER
ARG DB_PASS
ARG DB_NAME
ARG ENV
ARG APP_ENV

ENV COMPOSE_PROJECT_NAME=$COMPOSE_PROJECT_NAME
ENV INSTALL_XDEBUG=$INSTALL_XDEBUG
ENV DB_PORT=$DB_PORT
ENV DB_HOST=$DB_HOST
ENV DB_USER=$DB_USER
ENV DB_PASS=$DB_PASS
ENV DB_NAME=$DB_NAME
ENV ENV=$ENV
ENV APP_ENV=$ENV

RUN echo "------> ENV ${ENV}"

RUN apt-get update && apt-get install -y \
    gnupg \
    g++ \
    procps \
    openssl \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    libxslt1-dev \
    libpq-dev \
    acl \
    curl \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

# Xdebug
ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} = true ]; \
    then \
      pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug; \
    fi;

RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

RUN docker-php-ext-configure gd --with-jpeg --with-freetype

RUN docker-php-ext-install \
   pdo pdo_pgsql zip xsl gd intl opcache exif mbstring

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./.docker/php-fpm/xdebug.ini "${PHP_INI_DIR}/conf.d"
COPY ./.docker/php-fpm/error_reporting.ini "${PHP_INI_DIR}/conf.d"
COPY ./.docker/php-fpm/start.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start

WORKDIR /var/www
COPY ./backend /var/www
CMD ["start"]
