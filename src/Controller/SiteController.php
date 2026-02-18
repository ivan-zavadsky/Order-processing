<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SiteController extends AbstractController
{
    #[Route('/', name: 'homepage', stateless: true)]
    public function index(): Response
    {
//        echo '<pre>';
//        var_dump(
//            'here'
//        );
//        echo '</pre>';
//        die;

        return $this->redirectToRoute(
            'app_order_index',
            [],
            Response::HTTP_SEE_OTHER
        );
    }
}
