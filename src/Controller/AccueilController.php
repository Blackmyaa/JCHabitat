<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'page_title' => 'JC Habitat & Services- Remplacement de cumulus',
            'meta_description' => 'JC Habitat, spécialiste du remplacement et dépannage de cumulus. Intervention rapide et garantie.'
        ]);

    }
}
