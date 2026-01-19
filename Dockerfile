FROM php:8.2-apache

# Enable required PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Enable Apache rewrite (optional but good)
RUN a2enmod rewrite

# Copy project files to Apache root
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
