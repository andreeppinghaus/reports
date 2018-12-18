FROM diogok/php7

ENV PHP_ENV production
COPY vendor /var/www/vendor
COPY config /var/www/config
COPY html /var/www/html
COPY src /var/www/src
COPY install_pdf.sh /var/www/
COPY cria_data.sh /var/www/
RUN chown www-data.www-data /var/www/html -R
RUN usermod -a -G www-data www-data
RUN chgrp -R www-data /var/www/html
RUN chmod -R g+w /var/www/html
#RUN apt-get update && apt-get install mbstring
#RUN  echo "#Alteracao do rc.local para criacao de pasta para exportacao de csv" > /etc/rc.local \
#    && echo "mkdir /var/www/html/data" >> /etc/rc.local \
#    && echo "chmod 775 /var/www/html/data" >> /etc/rc.local
