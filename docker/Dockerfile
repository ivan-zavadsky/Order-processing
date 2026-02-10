FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    libonig-dev \
    libzip-dev \
    unzip

RUN docker-php-ext-install mysqli pdo pdo_mysql curl mbstring

RUN apt-get update
RUN apt-get install bash
RUN apt-get install curl
RUN yes | apt-get install zip

RUN curl -s https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

#RUN docker exec -it mik-3-php-fpm-1 composer create-project --prefer-dist yiisoft/yii2-app-basic app

#COPY script.sh /script.sh
#RUN chmod +x /script.sh
#ENTRYPOINT ["/script.sh"]
# Default command for the container
