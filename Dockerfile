FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli xml

COPY . /var/www/html/

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]