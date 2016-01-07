FROM cncflora/apache

RUN apt-get update && \
    apt-get install supervisor python-xlwt zip -y && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

EXPOSE 80

RUN mkdir /var/log/supervisord
ADD config/supervisor.conf /etc/supervisor/conf.d/base.conf
ADD config/not_cron.sh /var/www/not_cron.sh
CMD ["supervisord"]

ADD composer.json /var/www/composer.json
RUN composer self-update && composer install --no-dev

ADD config/settings.yml /var/www/config/settings.yml
ADD resources /var/www/resources
ADD html /var/www/html
ADD src /var/www/src

