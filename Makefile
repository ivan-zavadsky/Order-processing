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
up: # Deploy project
	composer install
	docker stop $(docker ps -a -q)
	docker rm $(docker ps -a -q)
	docker compose up -d
	docker compose exec php-fpm php bin/console \
	doctrine:fixtures:load --append
	docker compose exec php-fpm php bin/console \
    messenger:consume rabbitmq_orders

# ==================== Bash containers ==============================

redis:
	docker exec -it project_redis redis-cli
php:
	docker exec -it processing-php-fpm-1 bash

# ==================== Entity =======================================

entity:
	docker compose exec php-fpm php bin/console \
	make:entity

# ==================== Migrations ===================================

create: # Create migration
	docker compose exec php-fpm php bin/console make:migration
migrate: # Migrate
	docker compose exec php-fpm php bin/console \
	doctrine:migrations:migrate --no-interaction

# ==================== Rabbit =======================================

rabbit:
	docker compose exec php-fpm php bin/console \
    messenger:consume rabbitmq_orders --time-limit=3600 -vvv

# ==================== Cache ==============================

clear:
	docker compose exec php-fpm php bin/console cache:clear

# ==================== Fixtures ==============================

load: # Load fixtures
	docker compose exec php-fpm php bin/console \
	doctrine:fixtures:load
