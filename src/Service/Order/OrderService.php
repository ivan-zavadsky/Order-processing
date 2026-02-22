<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\User;
use App\Enum\OrderStatus;
use App\Message\OrderCreatedMessage;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class OrderService
{
    public function __construct(
        private OrderRepository     $orderRepository,
        private UserRepository      $userRepository,
        private ProductRepository   $productRepository,
        private MessageBusInterface $bus,
    ) {}

    /**
     * @throws ExceptionInterface
     */
    public function create(#[MapRequestPayload] OrderDto $dto)
        : Order
    {
        $order = new Order();
        $user = $this->userRepository
            ->findOneBy(['id' => $dto->userId]);

        if (!$user) {
            $order->setStatus(OrderStatus::FAILED);
        } else {
            $order->setUser($user);
            $order->setStatus(OrderStatus::NEW);
        }

        foreach ($dto->items as $item) {
            $orderItem = new OrderItem();
            $product = $this->productRepository
                ->findOneById($item->productId);
            if (!$product) {
                throw new \Exception('Product id='
                    . $item->productId . ' not found');
            }
            $orderItem->setProduct($product);
            $orderItem->setQuantity($item->quantity);
            $orderItem->setPrice($product->getPrice());
            $order->addItem($orderItem);
        }
        $orderId = $this->orderRepository->save($order);
        $order->setId($orderId);

        $this->bus->dispatch(
            new OrderCreatedMessage(
                  $order->getId()
            )
        );

        return $order;
    }
}
