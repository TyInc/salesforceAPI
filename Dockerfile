FROM registry.ty.com/oracle-php-oci19.9:7.4

RUN apt-get update && apt-get install -y libpng-dev libjpeg62-turbo-dev libfreetype6-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN docker-php-ext-install gd

RUN a2enmod rewrite allowmethods

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN sed -ri -e 's!upload_max_filesize = 2M!upload_max_filesize = 500M!g' "$PHP_INI_DIR/php.ini"
RUN sed -ri -e 's!post_max_size = 8M!post_max_size = 500M!g' "$PHP_INI_DIR/php.ini"
RUN sed -ri -e 's!memory_limit = 128M!memory_limit = 500M!g' "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html

COPY --chown=www-data:www-data composer.json ./
COPY --chown=www-data:www-data composer.lock ./

RUN composer self-update --no-progress
RUN composer install --no-autoloader --quiet --no-scripts

COPY --chown=www-data:www-data . .

RUN composer dump-autoload

EXPOSE 80

ENTRYPOINT ["./entrypoint.sh"]
