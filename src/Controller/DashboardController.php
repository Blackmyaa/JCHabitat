<?php

namespace App\Controller;

use App\Entity\Devis;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function index(EntityManagerInterface $em): Response
    {
        // Vérifie que l'utilisateur est connecté et est admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupère les 20 derniers devis actifs (nouveau et en cours)
        $devisRepo = $em->getRepository(Devis::class);
        $dernierDevis = $devisRepo->findActiveDevis([], ['dateCreation' => 'DESC'],20);

        return $this->render('Admin/dashboard/dashboard.html.twig', [
            'devis' => $dernierDevis,
        ]);
    }
}


