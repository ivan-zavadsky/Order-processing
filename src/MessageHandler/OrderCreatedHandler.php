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

        $order = $this->orderRepository
            ->findObjectWithRelations($message->orderId);
        $order->setStatus(OrderStatus::PROCESSING);
        $this->orderRepository->save($order);

        $orderDto = new \stdClass();
        $orderDto->id = $order->getId();
        $orderDto->userId = $order->getUserId();
        $orderDto->status = $order->getStatus()->value;
        $orderDto->items = [];
        foreach ($order->getItems() as $item) {
            $itemDto = new \stdClass();
            $itemDto->id = $item->getId();
            $itemDto->product = $item->getProduct()->getName();
            $itemDto->price = $item->getProduct()->getPrice();
            $itemDto->quantity = $item->getQuantity();

            $orderDto->items[] = $itemDto;
        }


        //Тут мы именно сохраняем в Редис
        $this->cache->get(
            'order_' . $order->getId(),
            function (ItemInterface $item) use ($orderDto) {
                $item->expiresAfter(3600); // 1 час

                return $orderDto;
            }
        );

    }
}
