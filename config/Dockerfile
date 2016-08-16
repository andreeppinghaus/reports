FROM diogok/php7

COPY vendor /var/www/vendor
COPY config /var/www/config
COPY html /var/www/html
COPY src /var/www/src
RUN chown www-data.www-data /var/www/html/data -R
RUN chmod -R 777 /var/www/html/data
