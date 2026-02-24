<?php

declare(strict_types=1);

namespace App\Controller\Order\Web;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\Order\Web\Actions\IndexAction;
use App\Controller\Order\Web\Actions\ShowAction;
use App\Controller\Order\Web\Actions\DeleteSelectedAction;
use Psr\Cache\InvalidArgumentException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[Route('/order')]
final class OrderController extends AbstractController
{
    public function __construct(
        private readonly IndexAction $indexAction,
        private readonly ShowAction $showAction,
        private readonly DeleteSelectedAction $deleteSelectedAction,
    ) {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route(name: 'app_order_index', methods: ['GET'])]
    public function index()
        : Response
    {
        return ($this->indexAction)();
    }

    /**
     * @throws SyntaxError
     * @throws InvalidArgumentException
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route('/{id}', name: 'app_order_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(
        Request $request,
    )
        : Response
    {
        return ($this->showAction)(
            (int) $request->query->get('id'),
        );
    }

    #[Route('/delete-selected', name: 'app_order_delete_selected', methods: ['POST'])]
    public function deleteSelected(
        Request $request,
    )
        : Response
    {
        return ($this->deleteSelectedAction)(
            $request,
        );
    }
}
