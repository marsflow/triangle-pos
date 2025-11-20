FROM php:8.1-fpm-bullseye


WORKDIR /app

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \


    wkhtmltopdf \
    libxrender1 \
    libxext6 \
    libfontconfig1 \



    libzip-dev \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    zip \
    git \
   
  && docker-php-ext-install zip pdo pdo_mysql pdo_pgsql \
  && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install gd \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install exif && docker-php-ext-enable exif

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# Verify Composer
RUN composer --version

# Copy application files
COPY . /app

# Install dependencies
RUN composer install --ignore-platform-reqs --no-interaction --prefer-dist --optimize-autoloader

# Make sure startup script is executable
RUN chmod +x /app/docker-startup.sh

ENTRYPOINT ["/app/docker-startup.sh"]
