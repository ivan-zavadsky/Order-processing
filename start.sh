#!/bin/bash

# Получаем динамически назначенный порт RabbitMQ
RABBITMQ_PORT=$(docker inspect --format '{{(index .NetworkSettings.Ports "5672/tcp")[0].HostPort}}' rabbitmq)

# Получаем динамически назначенный порт для RabbitMQ Management
RABBITMQ_MANAGEMENT_PORT=$(docker inspect --format '{{(index .NetworkSettings.Ports "15672/tcp")[0].HostPort}}' rabbitmq)

# Проверка, удалось ли получить порты
if [ -z "$RABBITMQ_PORT" ]; then
  echo "Ошибка: не удалось получить порт RabbitMQ!"
  exit 1
fi

if [ -z "$RABBITMQ_MANAGEMENT_PORT" ]; then
  echo "Ошибка: не удалось получить порт RabbitMQ Management!"
  exit 1
fi

# Экспортируем переменные окружения для использования в Docker Compose
export RABBITMQ_PORT
export RABBITMQ_MANAGEMENT_PORT

echo "RabbitMQ порты: $RABBITMQ_PORT, $RABBITMQ_MANAGEMENT_PORT"

# Запускаем Docker Compose с экспортированными переменными
#docker-compose up -d
