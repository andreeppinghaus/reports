FROM cncflora/apache

RUN apt-get update && \
    apt-get install supervisor python-xlwt zip -y && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

ADD default.conf /etc/apache2/sites-available/000-default.conf
ADD .htaccess /var/www/.htaccess
ADD .htpasswd /var/www/.htpasswd

EXPOSE 80

RUN mkdir /var/log/supervisord
ADD supervisor.conf /etc/supervisor/conf.d/base.conf
CMD ["supervisord"]

COPY . /var/www/
COPY data/index.php /var/www/data/index.php
RUN chown www-data.www-data /var/www/ -Rf

