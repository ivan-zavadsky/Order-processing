<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\Entity\Order;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class OrderService
{
    public function __construct(
        private OrderRepository $orderRepository
    ) {}
    public function create(#[MapRequestPayload] OrderDto $dto)
    {
        $order = new Order($dto->userId);
        foreach ($dto->items as $item) {
            $order->addItem($item);
        }

        $order->setStatus('NEW');

        $this->orderRepository->save($order);

        return $order;
    }
}
