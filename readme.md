README

## Tech stack
- PHP 8.4
- Symfony 7
- MySql
- RabbitMQ
- Redis
- Docker
- PHPUnit

## Features
- Clean architecture
- Thin controllers
- Async processing with Messenger
- Unit & Functional tests

## Architecture Overview

```mermaid
flowchart LR
    A[Request] --> B[Controller]
    B --> C[DTO]
    C --> D[OrderService]
    D --> E[(Database)]
    D --> F[RabbitMQ]
    F --> G[Consumer]
    G --> H[(Redis)]
```
