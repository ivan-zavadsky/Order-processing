<?php

namespace App\Controller\Order\Actions;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class DeleteAction
{
    public function __construct(
        private readonly CsrfTokenManagerInterface $csrfTokenManager
    ) {
    }

    public function __invoke(
        Request $request,
        Order $order,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return new Response('', Response::HTTP_SEE_OTHER, [
            'Location' => '/order'
        ]);
    }

    private function isCsrfTokenValid(string $id, ?string $token): bool
    {
        return $this->csrfTokenManager->isTokenValid(new \Symfony\Component\Security\Csrf\CsrfToken($id, $token));
    }
}
