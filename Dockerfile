FROM php:8.3-apache

RUN apt-get update && apt-get install -y --no-install-recommends libpq-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo_pgsql curl \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/app
COPY . /var/www/app
RUN ln -s ../public /var/www/app/webroot/public \
    && mkdir -p /var/www/app/public/uploads/profiles /var/www/app/public/uploads/cars \
    && chown -R www-data:www-data /var/www/app/public/uploads

COPY deploy/apache.conf /etc/apache2/sites-available/000-default.conf
COPY deploy/start-apache.sh /usr/local/bin/start-apache
RUN chmod +x /usr/local/bin/start-apache

CMD ["start-apache"]
