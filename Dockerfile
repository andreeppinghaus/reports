FROM cncflora/ruby

RUN gem install bundler

RUN mkdir /root/reports
ADD Gemfile /root/reports/Gemfile
RUN cd /root/reports && bundle install

EXPOSE 80
WORKDIR /root/reports
CMD ["unicorn","-p","80"]

ADD . /root/reports

