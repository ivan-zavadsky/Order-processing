# Useful commands

# Migrations

````bash
docker compose exec php-fpm php bin/console \
make:migration
````
````bash
docker compose exec php-fpm php bin/console \
doctrine:migrations:migrate --no-interaction
````
# Cache
````bash
docker exec -it processing-php-fpm-1 bash php bin/console cache:clear
````
# Rabbit
````bash
docker exec -it processing-php-fpm-1 bash 
php bin/console messenger:consume rabbitmq_orders -vv
````
````bash
docker compose exec php-fpm php bin/console \
messenger:consume rabbitmq_orders --time-limit=3600 -vvv
````
# Docker
````bash
docker compose down
docker compose build --no-cache
docker compose up -d --build
````
````bash
docker stop $(docker ps -a -q)
````
````bash
docker compose up -d
````
````bash
docker ps
````
# Bash containers
````bash
docker exec -it project_redis redis-cli
````
````bash
docker exec -it processing-php-fpm-1 bash
````
