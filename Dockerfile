FROM php:8.4-apache
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN a2enmod rewrite \
    && apt-get update \
    && apt-get install -y --no-install-recommends libzip-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev libxml2-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql zip gd mbstring dom simplexml xml xmlreader xmlwriter \
    && rm -rf /var/lib/apt/lists/*
