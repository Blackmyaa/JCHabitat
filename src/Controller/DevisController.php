<?php

namespace App\Controller;

use App\Form\DevisType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class DevisController extends AbstractController
{
    #[Route('/devis', name: 'app_devis')]
    public function devis(Request $request): Response
    {
        $form = $this->createForm(DevisType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Ici tu peux :
            // - envoyer un email
            // - enregistrer en BDD
            // - afficher une confirmation

            $this->addFlash('success', 'Votre demande de devis a bien été envoyé !');

            return $this->redirectToRoute('app_accueil');
        }
        return $this->render('devis/demandeDevis.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
