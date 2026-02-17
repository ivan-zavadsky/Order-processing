<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Form\OrderType;
use App\Message\OrderCreatedMessage;
use App\MessageHandler\OrderCreatedHandler;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/order')]
final class OrderController extends AbstractController
{
    #[Route(name: 'app_order_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_order_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
    )
        : Response
    {
        $order = new Order(1);
        $orderItem = new OrderItem();
        $orderItem->setQuantity(1);
        $order->addItem($orderItem);
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData()->getItems();
            $order = new Order(1);
            $order->setUserId(1);

            foreach ($data as $item) {

                /** @var Product $product */
                $product = $item->getProduct();
                $quantity = $item->getQuantity();

                // цена из базы
                $price = $product->getPrice();
                $total = $price * $quantity;

                // создаём сущность OrderItem
                $orderItem = new OrderItem();
                $orderItem->setProduct($product);
                $orderItem->setQuantity($quantity);
                // фиксируем цену на момент заказа
                $orderItem->setPrice($price);
//                $orderItem->setTotal($total);

                $entityManager->persist($orderItem);

                $order->addItem($orderItem);
                $entityManager->persist($order);
            }


            $entityManager->flush();

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order/new.html.twig', [
//            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_order_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(
        Order $order,
        OrderRepository $orderRepository
    )
        : Response
    {
        $order = $orderRepository
            ->findOneWithRelations($order->getId());

        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_order_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('order/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_order_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete-selected', name: 'app_order_delete_selected', methods: ['POST'])]
    public function deleteSelected(
        Request $request,
        OrderRepository $orderRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'] ?? [];

        foreach ($ids as $id) {
            $order = $orderRepository->find($id);
            if ($order) {
                $entityManager->remove($order);
            }
        }

        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/check', name: 'app_order_check', methods: ['GET'])]
    public function check(
        Request $request,
        OrderRepository $orderRepository,
        CacheInterface $cache,
    )
        : Response
    {
        $lastId = $orderRepository->findLastId();
        $order = $cache->get(
            'order_' . $lastId,
            function (ItemInterface $item) {
                return null;
        });
//dump($lastId, $order);
//die;
        return $this->render('order/check.html.twig', [
            'orders' => [$order],
        ]);
    }
    #[Route('/dump_order_handler', name: 'app_order_dump_order_handler', methods: ['GET'])]
    public function dump_order_handler(
        Request $request,
        OrderRepository $orderRepository,
        OrderCreatedHandler $orderCreatedHandler,
    )
//        : Response
    {
        $lastId = $orderRepository->findLastId();
        $message = new OrderCreatedMessage(
            orderId: $lastId,
            userId:  1
        );
        $orderCreatedHandler($message);

//        return $this->render('order/check.html.twig', [
//            'orders' => [$order],
//        ]);
    }
}
