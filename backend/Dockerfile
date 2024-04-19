FROM php:8.3.4RC1-fpm-bullseye

ARG UID
ARG GID

ENV UID=${UID}
ENV GID=${GID}

RUN mkdir -p /var/www/html
WORKDIR /var/www/html

# Install PDO extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Update and install system packages
RUN apt-get update && apt-get install -y git unzip \
    && rm -rf /var/lib/apt/lists/*

# Install memcached
RUN apt-get update && apt-get install -y libmemcached-dev zlib1g-dev \
    && pecl install memcached \
    && docker-php-ext-enable memcached

RUN delgroup dialout
RUN addgroup --gid ${GID} --system laravel
RUN adduser --group --system --disabled-password -shell /bin/sh -u ${UID} laravel
RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf


USER laravel
CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]

