FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    unzip \
    git \
  && docker-php-ext-install \
    pdo_mysql \
    bcmath \
    gd \
    zip \
  && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

ENTRYPOINT ["php"]
