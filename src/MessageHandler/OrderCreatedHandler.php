<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Enum\OrderStatus;
use App\Message\OrderCreatedMessage;
use App\Repository\OrderRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[AsMessageHandler]
readonly class OrderCreatedHandler
{
    public function __construct(
        private OrderRepository $orderRepository,
        private CacheInterface $cache,
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(
        OrderCreatedMessage $message
    )
        : void
    {
        sleep(5);

//        $order = $this->orderRepository
//            ->findObjectWithRelations($message->orderId);
        $order = $message->order;
        if (!$order) {
            return;
        }
        $order->setStatus(OrderStatus::PROCESSING);
        $this->orderRepository->save($order);

        // Формируем данные в том же формате, что и в findOneWithRelations (getScalarResult)
        $orderItemsData = [];
        foreach ($order->getItems() as $item) {
            $orderItemsData[] = [
//                'id' => $order->getId(),
//                'orderItemId' => $item->getId(),
                'name' => $item->getProduct()->getName(),
                'price' => $item->getProduct()->getPrice(),
                'quantity' => $item->getQuantity()
            ];
        }
        $orderData = [
            'id' => $order->getId(),
            'status' => $order->getStatus(),
            'items' => $orderItemsData,
        ];

        // Сохраняем данные в кеш
        $this->cache->get(
            'order_' . $order->getId(),
            function (ItemInterface $item) use ($orderData) {
                $item->expiresAfter(3600); // 1 час

                return $orderData;
            }
        );

    }
}
