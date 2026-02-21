<?php

declare(strict_types=1);

namespace App\Service\Order;

class UpdateOrderDto
{
    public function __construct(
        public int $id,
        public ?string $userName,
        public string $status,
        /** OrderItemDto[] */
        public array $items
    )
    {

    }
}
