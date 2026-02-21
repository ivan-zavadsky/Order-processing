<?php

namespace App\Controller\Order\Api;

use App\Repository\OrderRepository;
use App\Service\Order\OrderDto;
use App\Service\Order\OrderItemDto;
use App\Service\Order\OrderService;
use App\Service\Order\UpdateOrderDto;
use App\Service\Order\UpdateOrderItemDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\ExceptionInterface as MessengerExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/order')]
final class OrderController extends AbstractController
{
    /**
     * @throws ExceptionInterface|MessengerExceptionInterface
     */
    #[Route('/new', name: 'app_api_order_new', methods: ['POST'])]
    public function new(
        Request $request,
        SerializerInterface $serializer,
        OrderService $orderService,
    )
        : JsonResponse
    {
        $orderData = $serializer->deserialize(
            $request->getContent(),
            OrderDto::class,
            'json'
        );
        $order = $orderService->create($orderData);

        return $this->json([
            'status' => $order->getStatus(),
        ]);
    }

    /**
     * Возвращает список всех заказов в формате JSON
     */
    #[Route('/all', name: 'app_api_orders', methods: ['GET'])]
    public function getAllOrders(
        OrderRepository $orderRepository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $orders = $orderRepository->findAll();
        // Сериализуем данные в JSON
        $data = [];
        foreach ($orders as $order) {
            $items = [];
            foreach ($order->getItems() as $item) {
                $items[] = new UpdateOrderItemDto(
                    productName: $item->getProduct()->getName(),
                    quantity: $item->getQuantity(),
                );
            }

            $data[] = new UpdateOrderDto(
                id: $order->getId(),
                userName: $order->getUser()?->getName() ?: null,
                status: $order->getStatus()->value,
                items: $items
            );
        }

        return $this->json($data);
    }

}
