.PHONY: ps restart stop up redis php create migrate rabbit clear

# =================== Docker ========================================

ps: # List all containers
	docker ps
restart:
	docker compose down
	docker compose build --no-cache
	docker compose up -d --build
stop:
	docker stop $(docker ps -a -q)
up:
	docker compose up -d

# ==================== Bash containers ==============================

redis:
	docker exec -it project_redis redis-cli
php:
	docker exec -it processing-php-fpm-1 bash

# ==================== Migrations ===================================

create: # Create migration
	docker compose exec php-fpm php bin/console make:migration
migrate: # Migrate
	docker compose exec php-fpm php bin/console doctrine:migrations:migrate --no-interaction

# ==================== Rabbit =======================================

rabbit:
	docker compose exec php-fpm php bin/console \
    messenger:consume rabbitmq_orders --time-limit=3600 -vvv

# ==================== Cache ==============================

clear:
	docker compose exec php-fpm php bin/console cache:clear
