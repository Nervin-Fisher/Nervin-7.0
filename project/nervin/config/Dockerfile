FROM php:8.1.0-fpm

# Установка зависимостей
RUN apt-get update && apt-get install -y \
        build-essential \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        locales \
        zip \
        jpegoptim optipng pngquant gifsicle \
        vim \
        unzip \
        git \
        curl \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        zip \
        && apt-get clean && rm -rf /var/lib/apt/lists/*

ENV TZ=Europe/Kiev
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN printf '[Date]\ndate.timezone="%s"\n', $TZ > /usr/local/etc/php/conf.d/tzone.ini
# Установка расширений PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo_mysql mysqli zip exif pcntl bcmath opcache

# Настройка рабочей директории
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
        chromium \
        chromium-driver \
        && rm -rf /var/lib/apt/lists/*

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Открытие порта PHP-FPM
EXPOSE 9000