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
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Копируем приложение
COPY . .

# Устанавливаем зависимости через Composer
RUN #composer install --no-interaction --optimize-autoloader
