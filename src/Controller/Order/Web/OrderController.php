<?php

declare(strict_types=1);

namespace App\Controller\Order\Web;

use App\Controller\Order\Web\Actions\CheckAction;
use App\Controller\Order\Web\Actions\DeleteAction;
use App\Controller\Order\Web\Actions\DeleteSelectedAction;
use App\Controller\Order\Web\Actions\DumpOrderHandlerAction;
use App\Controller\Order\Web\Actions\EditAction;
use App\Controller\Order\Web\Actions\IndexAction;
use App\Controller\Order\Web\Actions\NewAction;
use App\Controller\Order\Web\Actions\ShowAction;
use App\Entity\Order;
use App\MessageHandler\OrderCreatedHandler;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[Route('/order')]
final class OrderController extends AbstractController
{
    public function __construct(
        private readonly IndexAction $indexAction,
        private readonly NewAction $newAction,
        private readonly ShowAction $showAction,
        private readonly EditAction $editAction,
        private readonly DeleteAction $deleteAction,
        private readonly DeleteSelectedAction $deleteSelectedAction,
        private readonly CheckAction $checkAction,
        private readonly DumpOrderHandlerAction $dumpOrderHandlerAction
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route(name: 'app_order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {
        return ($this->indexAction)($orderRepository);
    }

    #[Route('/new', name: 'app_order_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        return ($this->newAction)($request, $entityManager);
    }

    #[Route('/{id}', name: 'app_order_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(
        Order $order,
        OrderRepository $orderRepository
    ): Response
    {
        return ($this->showAction)($order, $orderRepository);
    }

    #[Route('/{id}/edit', name: 'app_order_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        return ($this->editAction)($request, $order, $entityManager);
    }

    #[Route('/{id}', name: 'app_order_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        return ($this->deleteAction)($request, $order, $entityManager);
    }

    #[Route('/delete-selected', name: 'app_order_delete_selected', methods: ['POST'])]
    public function deleteSelected(
        Request $request,
        OrderRepository $orderRepository,
        EntityManagerInterface $entityManager
    ): Response {
        return ($this->deleteSelectedAction)($request, $orderRepository, $entityManager);
    }

    #[Route('/check', name: 'app_order_check', methods: ['GET'])]
    public function check(
        Request $request,
        OrderRepository $orderRepository,
        CacheInterface $cache,
    ): Response
    {
        return ($this->checkAction)($request, $orderRepository, $cache);
    }

    #[Route('/dump_order_handler', name: 'app_order_dump_order_handler', methods: ['GET'])]
    public function dump_order_handler(
        Request $request,
        OrderRepository $orderRepository,
        OrderCreatedHandler $orderCreatedHandler,
    ): void {
        ($this->dumpOrderHandlerAction)($request, $orderRepository, $orderCreatedHandler);
    }

}
