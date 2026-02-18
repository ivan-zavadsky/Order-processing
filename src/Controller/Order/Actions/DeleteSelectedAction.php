<?php

namespace App\Controller\Order\Actions;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteSelectedAction
{
    public function __invoke(
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
}
