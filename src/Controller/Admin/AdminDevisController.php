<?php

namespace App\Controller\Admin;

use App\Entity\Devis;
use App\Entity\DevisLigne;
use App\Entity\DemandeDeDevis;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DemandeDeDevisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/devis')]
class AdminDevisController extends AbstractController
{
    #[Route('/create/{demandeId}', name: 'admin_devis_create')]
    public function create(
        int $demandeId,
        DemandeDeDevisRepository $demandeRepo,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $demande = $demandeRepo->find($demandeId);
        if (!$demande) {
            throw $this->createNotFoundException('Demande de devis introuvable.');
        }

        if (!$demande->getClient()) {
            $this->addFlash('danger', 'Aucun client associé à cette demande de devis.');
            return $this->redirectToRoute('admin_notifications_list');
        }

        if ($request->isMethod('POST')) {
            $devis = new Devis();
            $devis->setDemande($demande);
            $devis->setClient($demande->getClient());
            $devis->setAdministrateur($this->getUser());
            $devis->setDateCreation(new \DateTime());
            $devis->setNumeroDevis('DEV-' . date('YmdHis'));
            $devis->setStatut('en_cours');


            $linesData = $request->request->all('lines');
            $total = 0;

            if (is_array($linesData)) {
                foreach ($linesData as $line) {
                    if (empty($line['description']) || !isset($line['unitPrice'])) continue;

                    $quantity = (float) ($line['quantity'] ?? 1);
                    $unitPrice = (float) ($line['unitPrice'] ?? 0);
                    $subtotal = $quantity * $unitPrice;
                    $total += $subtotal;

                    // ✅ Création d'une ligne de devis
                    $ligne = new DevisLigne();
                    $ligne->setDescription($line['description']);
                    $ligne->setQuantity($quantity);
                    $ligne->setUnitPrice($unitPrice);
                    $ligne->setDevis($devis);

                    $em->persist($ligne);
                    $devis->addLigne($ligne);
                }
            }
            $devis->setMontantTotal($total);

            $em->persist($devis);
            $em->flush();

            $this->addFlash('success', 'Devis créé avec succès.');
            return $this->redirectToRoute('admin_devis_view', ['id' => $devis->getId()]);
        }

        return $this->render('admin/devis/createDevis.html.twig', [
            'demande' => $demande,
            'client' => $demande->getClient(),
        ]);
    }
    #[Route('/view/{id}', name: 'admin_devis_view', methods: ['GET'])]
public function view(
    int $id,
    EntityManagerInterface $em
): Response {
    $devis = $em->getRepository(Devis::class)->find($id);

    if (!$devis) {
        throw $this->createNotFoundException('Devis introuvable.');
    }

    $demande = $devis->getDemande();
    $client = $devis->getClient();

    return $this->render('Admin/devis/view.html.twig', [
        'devis' => $devis,
        'demande' => $demande,
        'client' => $client,
    ]);
}

}
