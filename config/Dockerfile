FROM diogok/php7

ENV PHP_ENV production
COPY vendor /var/www/vendor
COPY config /var/www/config
COPY html /var/www/html
COPY src /var/www/src
RUN chown www-data.www-data /var/www/html -R
RUN usermod -a -G www-data www-data
RUN chgrp -R www-data /var/www/html
RUN chmod -R g+w /var/www/html
