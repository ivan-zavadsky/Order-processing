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
        $order = $this->orderRepository->findOneBy(
            ['id' => $message->orderId]
        );
        if (!$order) {
            return;
        }
        $order->setStatus(OrderStatus::PROCESSING);
        $this->orderRepository->save($order);

        // Формируем данные в том же формате, что и в findOneWithRelations (getScalarResult)
//        $orderItemsData = [];
//        foreach ($order->getItems() as $item) {
//            $orderItemsData[] = [
//                'name' => $item->getProduct()->getName(),
//                'price' => $item->getProduct()->getPrice(),
//                'quantity' => $item->getQuantity()
//            ];
//        }
//        $orderData = [
//            'id' => $order->getId(),
//            'status' => $order->getStatus(),
//            'items' => $orderItemsData,
//        ];

        // Сохраняем данные в кеш
        $item = $this->cache->getItem('my_key');
        $item->set('my_value');
        $item->expiresAfter(3600);

        $this->cache->save($item); // This "puts" the data into Redis


//        $this->cache->get(
//            'order_' . $order->getId(),
//            function (ItemInterface $item) use ($order) {
//                $item->expiresAfter(3600); // 1 час
//
//                return $order;
//            }
//        );

        sleep(5);
    }
}
