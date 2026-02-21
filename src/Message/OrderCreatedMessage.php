<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\Order;
use App\Enum\OrderStatus;

readonly class OrderCreatedMessage
{
    public function __construct(
        public Order $order,
//        public int $orderId,
//        public int $userId
    ) {}
}
