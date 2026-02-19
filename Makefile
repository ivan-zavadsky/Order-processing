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

	#composer install
up: # Deploy project
	-@docker stop $$(docker ps -a -q) 2>/dev/null || true
	-@docker rm $$(docker ps -a -q) 2>/dev/null || true
	docker compose up -d
		docker compose exec php-fpm sh -c '\
    	  echo "Waiting for DB..."; \
    	  until nc -z mysql-db 3306; do sleep 1; done; \
    	  php bin/console doctrine:migrations:migrate --no-interaction \
    	'
	docker compose exec php-fpm php bin/console doctrine:fixtures:load
	@echo "Project up. Запустите consumer отдельно: make rabbit"

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
