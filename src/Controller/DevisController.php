<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Client;
use DateTimeImmutable;
use App\Form\DevisType;
use App\Entity\DemandeDeDevis;
use App\Entity\NotificationAdmin;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class DevisController extends AbstractController
{
    #[Route('/demande-devis', name: 'app_demande_devis')]
    public function devis(Request $request,EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        
        $demande = new DemandeDeDevis();
        $form = $this->createForm(DevisType::class,$demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //sauvegarde de la demande
            $em->persist($demande);
            $em->flush();

            //Gestion de la notification
            $notif = new NotificationAdmin();
            $notif->setMessage('Nouvelle demande de devis de ' . $demande->getNom() . ' ' . $demande->getPrenom());
            $notif->setCreatedAt(new DateTimeImmutable());
            $notif->setIsRead(false);
            $notif->setDemande($demande); // 🔗 lien direct avec la demande correspondante

            $em->persist($notif);
            $em->flush();

            // Ici tu peux :
            // - envoyer un email
            // - enregistrer en BDD
            // - afficher une confirmation
            
            $this->addFlash('success', 'Votre demande de devis a bien été envoyé !');
            return $this->redirectToRoute('app_client_create_from_demande', [
                'demandeId' => $demande->getId(),
            ]);
        }

        return $this->render('devis/demandeDevis.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
