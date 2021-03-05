ARG PHP_VERSION=7.4
ARG APCU_VERSION=5.1.17

# Base image for dev and prod
FROM php:${PHP_VERSION}-apache as app-base

ARG APCU_VERSION

WORKDIR /etc/apache2/sites-enabled/

RUN rm /etc/apache2/sites-enabled/000-default.conf
COPY docker/vhosts/apache.conf apache.conf

RUN a2enmod rewrite && a2enmod headers;

# Install paquet requirements
WORKDIR /var/www/app/

RUN set -ex; \
    # Install required system packages
    apt-get update; \
    apt-get install -qy --no-install-recommends \
            zlib1g-dev \
            libzip-dev \
            libpq-dev \
            libicu-dev \
            libcurl4-openssl-dev \
            pkg-config libssl-dev \
            git;

# Install php extensions
RUN set -ex; \
    docker-php-ext-install -j "$(nproc)" \
            pdo \
            zip \
            bcmath \
    ; \
    pecl install \
            apcu-${APCU_VERSION} \
    ; \
    docker-php-ext-enable \
            opcache \
            apcu \
    ; \
    docker-php-source delete; \
    # Clean aptitude cache and tmp directory
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*;

#Install ext-mongodb
RUN pecl install mongodb && \
    echo "extension=mongodb.so"> /usr/local/etc/php/conf.d/mongodb.ini ;

## set recommended PHP.ini settings
COPY docker/php.ini /usr/local/etc/php/php.ini
COPY docker/php-cli.ini /usr/local/etc/php/php-cli.ini


#####################################
##             APP DEV             ##
#####################################
FROM app-base as app-dev

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_MEMORY_LIMIT -1

# Install paquet requirements
RUN set -ex; \
    # Install required system packages
    apt-get update; \
    apt-get install -qy --no-install-recommends \
            unzip \
    ; \
    # Clean aptitude cache and tmp directory
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*;

# Install Composer
COPY --from=composer:1.7 /usr/bin/composer /usr/bin/composer

# Edit OPCache configuration
RUN set -ex; \
    { \
        echo 'opcache.validate_timestamps = 1'; \
        echo 'opcache.revalidate_freq = 0'; \
    } >> /usr/local/etc/php/php.ini

#####################################
##             APP PROD            ##
#####################################

FROM app-base as php-dependencies

COPY --from=composer:1.7 /usr/bin/composer /usr/bin/composer
COPY . /var/www/app/
WORKDIR /var/www/app/

RUN APP_ENV=prod composer install -o -n --no-dev --no-ansi && \
    APP_ENV=prod composer dump-autoload --optimize

FROM app-base as app-prod

COPY --from=php-dependencies /var/www/app/ /var/www/app/
ENV APP_ENV prod

WORKDIR /var/www/app/
RUN rm -rf var/cache && \
    rm -rf var/log && \
    mkdir -p var/cache && \
    chmod -R 777 var/ && \
    APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear && \
    chmod -R 777 var/cache && \
    chmod -R 777 var/log

RUN set -ex; \
    { \
        echo 'opcache.validate_timestamps = 0'; \
        echo 'session.gc_maxlifetime = 604800'; \
    } >> /usr/local/etc/php/php.ini
