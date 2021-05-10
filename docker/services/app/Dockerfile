FROM php:8.0-fpm

# Arguments defined in docker-compose.yml
ARG uname
ARG gid
ARG uid

# Install system dependencies
RUN apt-get update \
    && apt-get install -y \
        git \
        curl \
        dpkg-dev \
        libpng-dev \
        libjpeg-dev \
        libonig-dev \
        libxml2-dev \
        libpq-dev \
        libzip-dev \
        zip \
        unzip \
        cron

ADD ./php.ini /usr/local/etc/php/php.ini

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath gd sockets zip

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN pecl install redis
RUN docker-php-ext-enable redis

RUN docker-php-ext-configure gd \
  --enable-gd \
  --with-jpeg

# Get latest Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN mkdir -p /home/$uname/.composer

# Create system user to run Composer and Artisan Commands
RUN groupadd --gid $gid $uname
RUN useradd -G www-data,root -s /bin/bash --uid $uid --gid $gid $uname

RUN chown -R $uname:$uname /home/$uname

# Set working directory
WORKDIR /var/www

USER $uname

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
