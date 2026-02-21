.PHONY: ps restart stop up redis php create migrate rabbit clear load

# =================== Docker ========================================

# Список всех контейнеров
ps:
	docker ps

# Перезапуск контейнеров с пересборкой
restart:
	docker compose down
	docker compose build --no-cache
	docker compose up -d --build

# Остановка всех контейнеров
stop:
	docker stop $(docker ps -a -q)

# Разворачивание проекта
up: # Deploy project
	-@docker stop $$(docker ps -a -q) 2>/dev/null || true
	-@docker rm $$(docker ps -a -q) 2>/dev/null || true
	docker compose up -d
	docker compose exec php-fpm composer install
	docker compose exec php-fpm sh -c 'echo "Waiting for DB..."; \
	until php -r "try { new PDO(\"mysql:host=mysql-db;dbname=db\",\"user\",\"pass\"); } catch(Exception $e) { exit(1); }"; \
	do sleep 1; done; \
	php bin/console doctrine:migrations:migrate --no-interaction'
	docker compose exec php-fpm php bin/console doctrine:fixtures:load --append
	@echo "Project up. Запустите consumer отдельно: make rabbit"

# ==================== Bash containers ==============================

# Подключение к Redis контейнеру
redis:
	docker exec -it project_redis redis-cli

# Подключение к PHP-FPM контейнеру
php:
	docker exec -it processing-php-fpm-1 bash

# ==================== Entity =======================================

# Создание сущности с помощью make:entity
entity:
	docker compose exec php-fpm php bin/console make:entity

# ==================== Migrations ===================================

# Создание миграции
create:
	docker compose exec php-fpm php bin/console make:migration

# Выполнение миграций
migrate:
	docker compose exec php-fpm php bin/console doctrine:migrations:migrate --no-interaction

# ==================== Rabbit =======================================

# Запуск RabbitMQ consumer
rabbit:
	docker compose exec php-fpm php bin/console messenger:consume rabbitmq_orders --time-limit=3600 -v

# ==================== Cache =======================================

# Очистка кэша
clear:
	docker compose exec php-fpm php bin/console cache:clear

# ==================== Fixtures ====================================

# Загрузка фикстур
load:
	docker compose exec php-fpm php bin/console doctrine:fixtures:load
