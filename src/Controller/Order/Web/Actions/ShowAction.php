<?php

namespace App\Controller\Order\Web\Actions;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ShowAction
{
    public function __construct(
        private readonly Environment $twig
    ) {
    }

    public function __invoke(
        Order $order,
        OrderRepository $orderRepository
    ): Response {
        $order = $orderRepository
            ->findOneWithRelations($order->getId());

        return new Response($this->twig->render('order/show.html.twig', [
            'order' => $order,
        ]));
    }
}
