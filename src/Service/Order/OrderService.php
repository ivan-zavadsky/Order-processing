<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\OrderStatus;
use App\Message\OrderCreatedMessage;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class OrderService
{
    public function __construct(
        private OrderRepository     $orderRepository,
        private ProductRepository   $productRepository,
        private MessageBusInterface $bus,
    ) {}

    /**
     * @throws ExceptionInterface
     */
    public function create(#[MapRequestPayload] OrderDto $dto)
        : Order
    {
        $order = new Order($dto->userId);
        foreach ($dto->items as $item) {
            $orderItem = new OrderItem();
            $product = $this->productRepository
                ->findOneByName($item->product);
            $orderItem->setProduct($product);
            $orderItem->setQuantity($item->quantity);
            $orderItem->setPrice($product->getPrice());
            $order->addItem($orderItem);
        }
        $order->setStatus(OrderStatus::NEW);
        $orderId = $this->orderRepository->save($order);
        $order->setId($orderId);

        $this->bus->dispatch(
            new OrderCreatedMessage($order->getId(), $order->getUserId())
        );

        return $order;
    }
}
