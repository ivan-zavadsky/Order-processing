<?php

declare(strict_types=1);

namespace App\Application\Dto;

final class CreateOrderDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly array $items,
        public readonly int $totalPrice
    ) {}
}
