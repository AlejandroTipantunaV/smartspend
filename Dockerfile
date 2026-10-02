# Use the official PHP 8.2 image with Apache
FROM php:8.2-apache

# Install PDO MySQL extension for database connections
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache rewrite module for URL routing
RUN a2enmod rewrite

# Set the working directory inside the container
WORKDIR /var/www/html

# Expose port 80 for web traffic
EXPOSE 80
