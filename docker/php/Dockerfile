FROM php:8.3-fpm

# 必要なパッケージをインストール
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libjpeg-dev libfreetype6-dev zip \
    && docker-php-ext-install pdo_mysql gd

# Composer をインストール
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY ./conf.d/uploads.ini /usr/local/etc/php/conf.d/uploads.ini
