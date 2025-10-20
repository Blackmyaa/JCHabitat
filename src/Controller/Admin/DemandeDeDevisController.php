<?php

namespace App\Controller\Admin;

use App\Entity\DemandeDeDevis;
use App\Repository\DemandeDeDevisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class DemandeDeDevisController extends AbstractController
{
    #[Route('/demandes-devis', name: 'app_demandes_devis')]
    public function index(Request $request, DemandeDeDevisRepository $repository): Response
    {
        // Récupération des paramètres du formulaire de recherche / filtre
        $keyword = $request->query->get('search', '');
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');

        // On initialise le résultat selon les filtres reçus
        if ($keyword) {
            $demandes = $repository->search($keyword);
        } elseif ($startDate && $endDate) {
            $start = new \DateTime($startDate);
            $end = new \DateTime($endDate);
            $demandes = $repository->findBetweenDates($start, $end);
        } else {
            $demandes = $repository->findAllOrderedByDate();
        }

        return $this->render('Admin/devis/admin_demande_de_devis.html.twig', [
            'demandes' => $demandes,
            'search' => $keyword,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    #[Route('/demande-devis/{id}', name: 'app_demande_devis_show')]
    public function show(DemandeDeDevis $demande): Response
    {
        return $this->render('Admin/devis/detail_demande_de_devis.html.twig', [
            'demande' => $demande,
        ]);
    }


}
