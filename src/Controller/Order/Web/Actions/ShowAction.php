<?php

namespace App\Controller\Order\Web\Actions;

use App\Repository\OrderRepository;
use App\Service\CacheService;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Symfony\Component\HttpFoundation\Response;

readonly class ShowAction
{
    public function __construct(
        private CacheService    $cache,
        private Environment     $twig,
        private OrderRepository $orderRepository,
        #[Autowire(service: 'monolog.logger.my_channel')]
        private LoggerInterface $myLogger,

    ) {
    }

    /**
     * @param int $orderId
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws InvalidArgumentException
     */
    public function __invoke(
        int $orderId,
    )
        : Response
    {
        $cacheKey = 'order_' . $orderId;
        $order = unserialize($this->cache->getCacheValue($cacheKey));

        return new Response($this->twig->render(
            $order
                    ? 'order/show.html.twig'
                    : 'order/no_show.html.twig', [
                'order' => $order,
        ]));
    }
}
