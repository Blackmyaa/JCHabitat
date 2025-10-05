<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PrestationController extends AbstractController
{
    #[Route('/prestation', name: 'app_presta')]
    public function index(): Response
    {
        return $this->render('prestation/prestation-index.html.twig', [
            'controller_name' => 'PrestationController',
        ]);
    }
}
