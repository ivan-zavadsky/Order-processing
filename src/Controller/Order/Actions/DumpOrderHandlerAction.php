<?php

namespace App\Controller\Order\Actions;

use App\Message\OrderCreatedMessage;
use App\MessageHandler\OrderCreatedHandler;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;

class DumpOrderHandlerAction
{
    public function __invoke(
        Request $request,
        OrderRepository $orderRepository,
        OrderCreatedHandler $orderCreatedHandler,
    ): void {
        $lastId = $orderRepository->findLastId();
        $message = new OrderCreatedMessage(
            orderId: $lastId,
            userId:  1
        );
        $orderCreatedHandler($message);
    }
}
