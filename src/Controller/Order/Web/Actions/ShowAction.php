<?php

namespace App\Controller\Order\Web\Actions;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ShowAction
{
    public function __construct(
        private readonly Environment $twig,
        private readonly CacheInterface $cache
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws InvalidArgumentException
     */
    public function __invoke(
        Order $order,
        OrderRepository $orderRepository
    ): Response {
        $cacheKey = 'order_' . $order->getId();

        // Сначала пытаемся получить данные из кеша
        $orderData = $this->cache->get($cacheKey, function (ItemInterface $item) use ($order, $orderRepository) {
            // Если данных нет в кеше, получаем из базы данных
            $orderData = $orderRepository->findOneWithRelations($order->getId());

            // Сохраняем данные в кеш на 1 час
            $item->expiresAfter(3600);

            return $orderData;
        });

        return new Response($this->twig->render(
            'order/show.html.twig', [
            'order' => $orderData,
        ]));
    }
}
