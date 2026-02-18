<?php

namespace App\Controller\Order\Actions;

use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Environment;

class CheckAction
{
    public function __construct(
        private readonly Environment $twig
    ) {
    }

    public function __invoke(
        Request $request,
        OrderRepository $orderRepository,
        CacheInterface $cache,
    ): Response {
        $lastId = $orderRepository->findLastId();
        $order = $cache->get(
            'order_' . $lastId,
            function (ItemInterface $item) {
                return null;
        });

        return new Response($this->twig->render(
            'order/check.html.twig', [
            'orders' => [$order],
        ]));
    }
}
