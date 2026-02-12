<?php

namespace App\Controller\Api;

use App\Service\Order\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Order\OrderDto;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

#[Route('/api/order')]
final class OrderController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('/new', name: 'app_api_order_new', methods: ['POST'])]
    public function new(
        Request $request,
        Serializer $serializer,
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


}
