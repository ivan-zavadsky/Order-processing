<?php

declare(strict_types=1);

namespace App\Message;

readonly class OrderCreatedMessage
{
    public function __construct(
        public int $orderId,
        public int $userId
    ) {}
}
