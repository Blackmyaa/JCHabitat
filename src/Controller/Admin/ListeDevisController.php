<?php

namespace App\Controller\Admin;

use App\Repository\DevisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/admin/devis')]
class ListeDevisController extends AbstractController
{
    #[Route('/', name: 'admin_devis_index')]
    public function index(DevisRepository $devisRepository): Response
    {
        $devis = $devisRepository->findAll();

        return $this->render('Admin/devis/listeDevis.html.twig', [
            'devis' => $devis,
        ]);
    }

    #[Route('/filter', name: 'admin_devis_filter', methods: ['POST'])]
    public function filter(Request $request, DevisRepository $devisRepository): JsonResponse
    {
        $statuts = $request->request->all('statuts');
        $search = $request->request->get('search', '');
        $sort = $request->request->get('sort', 'date_desc');

        $qb = $devisRepository->createQueryBuilder('d')
            ->leftJoin('d.client', 'c');

        // Filtrage par statuts
        if (!empty($statuts)) {
            $qb->andWhere('d.statut IN (:statuts)')
               ->setParameter('statuts', $statuts);
        }

        // Recherche (par nom client ou numéro devis)
        if (!empty($search)) {
            $qb->andWhere('c.nom LIKE :search OR c.prenom LIKE :search OR d.numeroDevis LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        // Tri
        switch ($sort) {
            case 'date_asc':
                $qb->orderBy('d.dateCreation', 'ASC');
                break;
            case 'montant_asc':
                $qb->orderBy('d.montantTotal', 'ASC');
                break;
            case 'montant_desc':
                $qb->orderBy('d.montantTotal', 'DESC');
                break;
            default:
                $qb->orderBy('d.dateCreation', 'DESC');
        }

        $devis = $qb->getQuery()->getResult();

        $html = $this->renderView('Admin/commun/devis/_table.html.twig', [
            'devis' => $devis,
        ]);

        return new JsonResponse(['html' => $html]);
    }
}
