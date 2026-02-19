<?php

declare(strict_types=1);

namespace App\Service\Order;

use Symfony\Component\Validator\Constraints as Assert;

class OrderItemDto
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $productId;

    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $quantity;
}
