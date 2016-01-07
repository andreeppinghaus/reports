project = reports

all: build

install-deps:
	docker-compose -p $(project) -f config/docker-compose.yml run --no-deps $(project) composer install

update-deps:
	docker-compose -p $(project) -f config/docker-compose.yml  run --no-deps $(project) composer update

run: 
	docker-compose -p $(project) -f config/docker-compose.yml up

start: 
	docker-compose -p $(project) -f config/docker-compose.yml up -d

stop: 
	docker-compose -p $(project) -f config/docker-compose.yml stop
	docker-compose -p $(project) -f config/docker-compose.yml rm

build:
	docker build -t cncflora/$(project) -f config/Dockerfile .

push:
	docker push cncflora/$(project)

