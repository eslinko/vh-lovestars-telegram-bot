FROM php:8.1-apache

WORKDIR /app/web

RUN /bin/sh -c set -eux \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
    $PHPIZE_DEPS \
    ca-certificates \
    curl \
    acl \
    git \
    wget \
    xz-utils \
    && rm -rf /var/lib/apt/lists/*

# php extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
    	intl \
    	zip \
    	apcu \
		opcache

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_MEMORY_LIMIT -1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY --from=composer/composer:2-bin /composer /usr/bin/composer

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.* ./
RUN set -eux; \
	composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress; \
	composer clear-cache

# copy sources
COPY . ./
RUN rm -Rf .docker/

RUN set -eux; \
	composer dump-autoload --classmap-authoritative --no-dev;

RUN mv "/usr/local/etc/php/php.ini-production" "/usr/local/etc/php/php.ini"
COPY .docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/apache/ports.conf /etc/apache2/ports.conf

COPY .docker/php/php.ini /usr/local/etc/php/conf.d/user.ini

EXPOSE 8080