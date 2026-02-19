<?php

namespace App\Controller\Order\Web\Actions;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class NewAction
{
    public function __construct(
        private readonly Environment $twig,
        private readonly FormFactoryInterface $formFactory
    ) {
    }

    public function __invoke(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
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

            return new Response('', Response::HTTP_SEE_OTHER, [
                'Location' => '/order'
            ]);
        }

        return new Response($this->twig->render('order/new.html.twig', [
            'form' => $form->createView(),
        ]));
    }

    private function createForm(string $type, mixed $data, array $options = []): FormInterface
    {
        return $this->formFactory->create($type, $data, $options);
    }
}
