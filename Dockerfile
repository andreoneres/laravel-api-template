# Base image
FROM php:8.1.8-apache

# Define work directory
WORKDIR /var/www

# Copy the files to the directory that apache will serve
COPY . .

# Update the packages
RUN apt-get update

# Install the dependencies
RUN apt-get install -y unzip

RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install project dependencies
RUN composer install

# Configure apache2
RUN echo "ServerName localhost:8080" >> /etc/apache2/apache2.conf

COPY apache-site.conf /etc/apache2/sites-available

RUN a2ensite apache-site.conf
RUN a2dissite 000-default.conf
RUN a2enmod rewrite

# Generate application key
RUN php artisan key:generate

# Generate JWT secret
RUN php artisan jwt:secret

# Generate JWT certs
RUN php artisan jwt:generate-certs

# Configure permissions
RUN chmod -R 755 ./storage

RUN chmod -R 755 ./bootstrap/cache

RUN chown -R www-data:www-data .

# Restart apache2
RUN service apache2 restart
