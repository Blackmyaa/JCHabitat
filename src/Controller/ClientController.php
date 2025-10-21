<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\DemandeDeDevis;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\DemandeDeDevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/client/from-demande/{demandeId}', name: 'app_client_create_from_demande')]
    public function createFromDemande(
        int $demandeId,
        DemandeDeDevisRepository $demandeRepo,
        ClientRepository $clientRepo,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $demande = $demandeRepo->find($demandeId);
        if (!$demande) {
            throw $this->createNotFoundException('Demande de devis introuvable.');
        }

        // Vérifier si un client existe déjà avec cet email
        $existingClient = $clientRepo->findOneBy(['email' => $demande->getEmail()]);

        if ($existingClient) {
            // 🔗 Lier directement la demande à ce client existant
            $demande->setClient($existingClient);
            $em->flush();

            $this->addFlash('info', 'Vous êtes déjà enregistré. Votre demande a été liée à votre compte.');
            return $this->redirectToRoute('app_accueil');
        }

        // Sinon, on crée un nouveau client pré-rempli
        $client = new Client();
        $client->setNom($demande->getNom());
        $client->setPrenom($demande->getPrenom());
        $client->setEmail($demande->getEmail());
        $client->setVille($demande->getVille());
        $client->setTelephone($demande->getTelephone());

        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($client);
            $demande->setClient($client);
            $em->flush();

            $this->addFlash('success', 'Vos informations ont bien été enregistrées. Merci !');
            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('devis/clientFromDemande.html.twig', [
            'form' => $form->createView(),
            'demande' => $demande,
        ]);
    }
}