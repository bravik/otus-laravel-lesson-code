# Properly set permissions
# https://www.gnu.org/software/make/manual/html_node/Target_002dspecific.html

COMPOSE       := docker compose -f docker/docker-compose.yml --env-file .env
ISFACL :=$(shell command -v setfacl 2> /dev/null)
OSTYPE :=$(shell uname)
IAM    :=$(shell whoami)

# Старт контейнера
up:
	${COMPOSE} up -d

# Остановка контейнера и удаление всех данных
down:
	${COMPOSE} down

# Одноразовая инициализация проекта для dev-окружения
init: setup-dotenv down rebuild setfacl up
	${COMPOSE} exec -u sail laravel composer install
	${COMPOSE} exec laravel ./artisan key:generate
	${COMPOSE} exec laravel ./artisan db:wipe
	${COMPOSE} exec laravel ./artisan migrate --seed
	${COMPOSE} exec node npm install --verbose  --ignore-optional
	${COMPOSE} restart laravel
	${COMPOSE} restart node
	echo "---------------------------------"
	echo "Initialization complete!"

# Копирует .env.example в .env
setup-dotenv:
	@if [ -f .env ]; then echo "WARNING: '.env' file already exists. Please delete it first!!!!"; exit 1; fi
	cp .env.example .env

# Вход в shell контейнера с php
cli:
	${COMPOSE} exec -u sail laravel  bash

cli-node:
	${COMPOSE} exec node sh

# Запуск миграций
migrate:
	${COMPOSE} exec laravel ./artisan migrate

# Ребилд контейнеров
upgrade: stop rebuild setfacl up

# Фикс прав доступа
setfacl:
ifdef ISFACL
	sudo setfacl -dR -m u:1000:rwX -m u:${IAM}:rwX storage
	sudo setfacl -R -m u:1000:rwX -m u:${IAM}:rwX storage
else ifeq ($(OSTYPE),Darwin)
	sudo chown -R 1000 storage
	sudo chmod -R +a "${IAM} allow delete,write,append,file_inherit,directory_inherit" storage
else
	sudo chown -R 1000 storage
endif


rebuild: export DOCKER_BUILDKIT = 1
rebuild:
	${COMPOSE} build --pull --force-rm $(ARGS)


