<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\OrderStatus;
use App\Message\OrderCreatedMessage;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
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
        #[Autowire(service: 'monolog.logger.my_channel')]
        private readonly LoggerInterface $myLogger,
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
            $this->orderRepository->save($order);
            $this->bus->dispatch(
                new OrderCreatedMessage(
                    $order->getId()
                )
            );

            return $order;
        } else {
            $order->setUser($user);
            $order->setStatus(OrderStatus::NEW);
        }

        foreach ($dto->items as $item) {
            $orderItem = new OrderItem();
            $product = $this->productRepository
                ->findOneById($item->productId);
            if (!$product) {
                $order->setStatus(OrderStatus::MODIFIED);
                $this->orderRepository->save($order);
                continue;
//                throw new \Exception('Product id='
//                    . $item->productId . ' not found');
            }
            $orderItem->setProduct($product);
            $orderItem->setQuantity($item->quantity);
            $orderItem->setPrice($product->getPrice());
            $order->addItem($orderItem);
        }
        $this->orderRepository->save($order);

        $this->bus->dispatch(
            new OrderCreatedMessage(
                  $order->getId()
            )
        );

        return $order;
    }
}
