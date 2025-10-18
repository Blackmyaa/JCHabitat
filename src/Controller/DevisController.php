<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Entity\Client;
use App\Form\DevisType;
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
        $form = $this->createForm(DevisType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Création du client (ou récupération si déjà existant)
            $client = new Client();
            $client->setNom($data['nom']);
            $client->setPrenom($data['prenom']);
            $client->setEmail($data['email']);
            $client->setTelephone($data['telephone'] ?? null);
            $client->setVille($data['ville']);

            $em->persist($client);

            // Création du devis
            $devis = new Devis();
            $devis->setNumeroDevis(uniqid('DEV-'));
            $devis->setStatut('En attente');
            $devis->setMontantTotal(0); // tu peux calculer plus tard
            $devis->setClient($client);

             // ✉️ Envoi du mail à l'utilisateur
            $emailUser = (new Email())
                ->from('contact@tonsite.fr')
                ->to($client->getEmail())
                ->subject('Votre demande de devis')
                ->html($this->renderView('emails/devis_user.html.twig', [
                    'client' => $client,
                    'devis' => $devis,
                ]));

            // ✉️ Envoi du mail à l’admin
            $emailAdmin = (new Email())
                ->from('contact@tonsite.fr')
                ->to('admin@tonsite.fr')
                ->subject('Nouvelle demande de devis')
                ->html($this->renderView('emails/devis_admin.html.twig', [
                    'client' => $client,
                    'devis' => $devis,
                ]));

            $mailer->send($emailUser);
            $mailer->send($emailAdmin);
            $em->persist($devis);
            $em->flush();

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
