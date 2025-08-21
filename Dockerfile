FROM php:8.2-apache

# mysqli + pdo_mysql (pdo optional; your legacy code uses mysqli)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite (if you ever need clean URLs)
RUN a2enmod rewrite

# Basic PHP settings
RUN {       echo "upload_max_filesize=16M";       echo "post_max_size=16M";       echo "memory_limit=512M";       echo "max_execution_time=120";       echo "date.timezone=${PHP_TIMEZONE:-Asia/Manila}";     } > /usr/local/etc/php/conf.d/zz-custom.ini

WORKDIR /var/www/html
