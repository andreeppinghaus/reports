FROM cncflora/apache

COPY vendor /var/www/vendor
COPY config /var/www/config
COPY html /var/www/html
COPY src /var/www/src
RUN chown www-data.www-data /var/www/html/data -R

