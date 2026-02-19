FROM php:8.4-fpm

# Устанавливаем зависимости для PHP и Symfony
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    librabbitmq-dev \
    && docker-php-ext-install intl mbstring pdo pdo_mysql zip opcache \
    \
    # AMQP \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    \
    # Redis \
    && pecl install redis \
    && docker-php-ext-enable redis \
    \
    # Xdebug \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    \
    && rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y netcat-openbsd && rm -rf /var/lib/apt/lists/*

# Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Копируем приложение
COPY . .

# Устанавливаем зависимости через Composer
RUN composer install --no-interaction --optimize-autoloader
