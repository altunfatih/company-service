FROM php:8-cli

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install --no-install-recommends -y \
    libzip-dev \
    unzip \
    git \
    && apt-get autoremove -y \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install pcov

RUN docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-enable pcov \
    && docker-php-ext-install -j$(nproc) pdo_mysql

WORKDIR /var/www/api

ENTRYPOINT ["php"]

CMD ["artisan", "serve", "--host", "0.0.0.0", "--port", "80"]