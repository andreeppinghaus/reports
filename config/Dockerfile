FROM cncflora/apache

COPY . /var/www
RUN composer self-update && composer install
RUN chown www-data.www-data /var/www -R

