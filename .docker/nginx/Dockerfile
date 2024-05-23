FROM nginx:alpine

ENV COMPOSE_PROJECT_NAME=${COMPOSE_PROJECT_NAME}
ENV NGINX_HOST_HTTP_PORT=${NGINX_HOST_HTTP_PORT}
ENV ENV=${ENV}

RUN echo "------> ENV ${ENV}"

COPY ./.docker/nginx/nginx.conf /etc/nginx/
COPY ./.docker/nginx/default.conf /etc/nginx/conf.d/

RUN apk update && apk upgrade && apk add --no-cache bash

RUN set -x ; \
    addgroup -g 82 -S www-data ; \
    adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1

# Set upstream conf and remove the default conf
ARG PHP_UPSTREAM_CONTAINER=php-fpm
ARG PHP_UPSTREAM_PORT=9000
RUN echo "upstream php-upstream { server ${PHP_UPSTREAM_CONTAINER}:${PHP_UPSTREAM_PORT}; }" > /etc/nginx/conf.d/upstream.conf

COPY ./backend /var/www

EXPOSE 80