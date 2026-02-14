<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Enum\OrderStatus;
use App\Message\OrderCreatedMessage;
use App\Repository\OrderRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class OrderCreatedHandler
{
    public function __construct(
        private OrderRepository $orderRepository
    )
    {

    }
    public function __invoke(OrderCreatedMessage $message): void
    {
        $order = $this->orderRepository->find($message->orderId);
        $order->setStatus(OrderStatus::PROCESSING);
        $this->orderRepository->save($order);
        // обработка сообщения
//        dump(25);
//        dump($message->userId);
    }
}
