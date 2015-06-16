FROM cncflora/apache

RUN apt-get update && \
    apt-get install supervisor python-xlwt -y && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

ADD default.conf /etc/apache2/sites-available/default
ADD .htaccess /var/www/.htaccess
ADD .htpasswd /var/www/.htpasswd

EXPOSE 80

RUN mkdir /var/log/supervisord
ADD supervisor.conf /etc/supervisor/conf.d/base.conf
CMD ["supervisord"]

ADD . /var/www/
RUN chown www-data.www-data /var/www/ -Rf

