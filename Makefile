DOCKER_COMPOSE="$(shell which docker-compose)"
CONTAINER_PHP="php-unit"

# Цвета
G=\033[32m
Y=\033[33m
NC=\033[0m


help: ## Список команд
	@grep -E '(^[a-zA-Z_0-9-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) \
	| awk 'BEGIN {FS = ":.*?## "}; {printf "${G}%-24s${NC} %s\n", $$1, $$2}' \
	| sed -e 's/\[32m## /[33m/' && printf "\n";

.PHONY: help


init: generate-env up ci  right m-up test right help ## Инициализация проекта, поднятие контейнеров, установка прав

restart: down up ## Перезапуск контейнеров

up: ## Поднятие контейнеров
	docker-compose   --env-file .env up --build -d

down: ## Остановка контейнеров
	docker-compose   --env-file .env down --remove-orphans

generate-env: ## генерация .env файла
	@if [ ! -f .env ]; then \
    		cp .env.example .env && \
    		sed -i "s/^DB_PASSWORD=/DB_PASSWORD=$(shell openssl rand -hex 8)/" .env; \
    	fi

.PHONY: init restart sleep-5 up down generate-env


bash: ## Войти в контейнер
	${DOCKER_COMPOSE} exec -it ${CONTAINER_PHP} /bin/bash

ps: ## Просмотр запущенных контейнеров
	${DOCKER_COMPOSE} ps

logs: ## Просмотр логов в контейнерах
	${DOCKER_COMPOSE} logs -f

.PHONY: bash ps logs

ci: ## Composer install
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} composer install --no-interaction

cu: ## Composer update
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} composer update --no-interaction

.PHONY: ci cu


m-up: ## Установка миграций
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} php artisan migrate

m-rollback: ## Откат последней миграции
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} php artisan migrate:rollback

m-status: ## Статус миграций
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} php artisan migrate:status

.PHONY: m-up m-rollback m-status


cc: ## Сброс кэша
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} php artisan cache:clear

right: ## Установка прав
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} chown -R www-data:www-data . && \
	chmod -R guo+w storage

test: ## Запуск автотестов
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} php artisan test

sw-gen: ## Генерация сваггера
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} php artisan l5-swagger:generate

admin: ## Создание пользователя с правами администратора
	${DOCKER_COMPOSE} exec ${CONTAINER_PHP} php artisan user:create-admin

.PHONY: cc right test sw-gen
