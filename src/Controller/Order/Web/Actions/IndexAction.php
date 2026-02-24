<?php

namespace App\Controller\Order\Web\Actions;

use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class IndexAction
{
    public function __construct(
        private Environment $twig,
        private OrderRepository $orderRepository,
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function __invoke(): Response
    {
        //todo: get data from Redis

        return new Response($this->twig->render(
                'order/index.html.twig', [
                'orders' => $this->orderRepository->findAll(),
            ]
        ));
    }
}
