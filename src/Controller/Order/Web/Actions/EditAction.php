<?php

namespace App\Controller\Order\Web\Actions;

use App\Entity\Order;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class EditAction
{
    public function __construct(
        private readonly Environment $twig,
        private readonly FormFactoryInterface $formFactory
    ) {
    }

    public function __invoke(
        Request $request,
        Order $order,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return new Response('', Response::HTTP_SEE_OTHER, [
                'Location' => '/order'
            ]);
        }

        return new Response($this->twig->render('order/edit.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]));
    }

    private function createForm(string $type, mixed $data, array $options = []): FormInterface
    {
        return $this->formFactory->create($type, $data, $options);
    }
}
