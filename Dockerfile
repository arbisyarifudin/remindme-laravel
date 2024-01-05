# php official fpm image
FROM php:8.1-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# installs basic tools, then postgres ppa and postgresql-client packages
# (and some other required dependencies). It then installs and configures several php extensions
# including pdo_pgsql and redis. Finally, it downloads and installs composer in the image.
RUN apt-get update \
    && apt-get install -y cron gnupg curl wget ca-certificates unzip lsb-release libgd-dev libpng-dev \
    && wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | apt-key add - \
    && echo "deb http://apt.postgresql.org/pub/repos/apt/ `lsb_release -cs`-pgdg main" | tee  /etc/apt/sources.list.d/pgdg.list \
    && apt-get install -y \
        libicu-dev \
        libpq-dev \
        libzip-dev \
        postgresql-client \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install intl pdo pdo_pgsql pgsql zip bcmath pcntl exif gd \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install nodejs
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Copy startup script into the image
# COPY startup.sh /usr/local/bin/startup.sh

# Make the startup script executable
# RUN chmod +x /usr/local/bin/startup.sh\

# Set working directory
WORKDIR /var/www

# Copy composer files
COPY composer.json composer.lock ./

# Copy npm files
COPY package.json package-lock.json ./

# Copy project files
COPY . .

# Rename .env.example to .env
RUN mv .env.example .env

# Set environment variable to allow Composer plugins to run as superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

# Run composer install and npm install
RUN composer install --no-interaction
RUN npm install --legacy-peer-deps

# Run artisan commands
RUN php artisan key:generate
# RUN php artisan migrate --force --seed

# Build assets
RUN npm run build

# Run the startup script when the container starts
# CMD ["/usr/local/bin/startup.sh"]

# Copy entrypoint script to container
# COPY entrypoint.sh /usr/local/bin/
# RUN chmod +x /usr/local/bin/entrypoint.sh

# Set entry point
# ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Set user and expose port for php-fpm
USER $user
