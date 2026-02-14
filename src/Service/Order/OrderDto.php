<?php

declare(strict_types=1);

namespace App\Service\Order;

use Symfony\Component\Validator\Constraints as Assert;

class OrderDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $userId,
        /**
         * @var OrderItemDto[]
         */
        #[Assert\NotBlank]
        #[Assert\Valid]
        public array $items,

    ) {}
}
