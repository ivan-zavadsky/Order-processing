<?php

declare(strict_types=1);

namespace App\Service\Order;

class UpdateOrderItemDto
{
    public function __construct(
        public string $productName,
        public int $quantity,
    )
    {

    }
}
