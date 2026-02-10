<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Dto\CreateOrderDTO;
use App\Application\Handler\CreateOrderHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/api/v1/orders', methods: ['POST'])]
    public function create(
        Request $request,
        CreateOrderHandler $handler
    ): JsonResponse {
//        $dto = CreateOrderDTO::fromRequest($request);
//
//        $orderId = $handler->handle($dto);
        $orderId = 1;

        return $this->json(['id' => $orderId], 201);
    }}
