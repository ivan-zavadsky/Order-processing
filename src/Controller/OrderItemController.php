<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Form\OrderItemType;
use App\Form\OrderType;
use App\Form\ProductType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order_item')]
final class OrderItemController extends AbstractController
{
    #[Route(name: 'app_order_item_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('orderItem/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_order_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
//        $order = new Order();
//        $form = $this->createForm(OrderType::class, $order);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($order);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_order_index', [], Response::HTTP_SEE_OTHER);
//        }
        $orderItem = new OrderItem();
        $form = $this->createForm(OrderItemType::class, $orderItem);

        return $this->render('orderItem/new.html.twig', [
//            'order' => $order,
//            'product' => $product,
            'orderItem' => $orderItem,
            'form' => $form,
        ]);
    }

}
