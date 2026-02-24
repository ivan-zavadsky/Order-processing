<?php

namespace App\Controller\Order\Web\Actions;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class DeleteSelectedAction
{
    public function __construct(
        private OrderRepository        $orderRepository,
        private EntityManagerInterface $entityManager,
    ) {}
    public function __invoke(
        Request $request,
    )
        : Response
    {
        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'] ?? [];

        foreach ($ids as $id) {
            $order = $this->orderRepository->find($id);
            if ($order) {
                $this->entityManager->remove($order);
            }
        }

        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
