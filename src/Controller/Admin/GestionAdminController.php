<?php

namespace App\Controller\Admin;

use App\Form\AddAdminType;
use App\Form\EditAdminType;
use App\Entity\Administrateur;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AdministrateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/administrateur')]
class GestionAdminController extends AbstractController
{
    #[Route('/', name: 'admin_administrateur_index')]
    public function index(AdministrateurRepository $repo): Response
    {
        $administrateurs = $repo->findBy([], ['nom' => 'ASC']);

        return $this->render('Admin/gestion_admin/gestionAdmin.html.twig', [
            'administrateurs' => $administrateurs,
        ]);
    }

    #[Route('/new-admin', name: 'admin_administrateur_new')]
    public function newAdmin(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $administrateur = new Administrateur();

        $form = $this->createForm(AddAdminType::class, $administrateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash du mot de passe
            $hashedPassword = $hasher->hashPassword($administrateur, $administrateur->getPassword());
            $administrateur->setPassword($hashedPassword);
            $administrateur->setRoles(['ADMIN']);

            $em->persist($administrateur);
            $em->flush();

            $this->addFlash('success', 'Nouvel administrateur créé avec succès.');

            return $this->redirectToRoute('admin_dashboard');
        }

        
        return $this->render('Admin/gestion_admin/ajoutAdmin.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/modifier', name: 'admin_administrateur_edit')]
    public function edit(Administrateur $administrateur, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response 
    {
          // On sauvegarde les anciennes valeurs
        $oldNom = $administrateur->getNom();
        $oldEmail = $administrateur->getEmail();
        $oldPassword = $administrateur->getPassword();

        $form = $this->createForm(EditAdminType::class, $administrateur);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion du mot de passe
            $newPassword = $form->get('motDePasse')->getData();
            if (!empty($newPassword)) {
                $hashed = $passwordHasher->hashPassword($administrateur, $newPassword);
                $administrateur->setPassword($hashed);
            } else {
                $administrateur->setPassword($oldPassword);
            }

            // Conservation des valeurs non modifiées
            if (empty($administrateur->getNom())) {
                $administrateur->setNom($oldNom);
            }

            if (empty($administrateur->getEmail())) {
                $administrateur->setEmail($oldEmail);
            }

            $em->flush();
        
            $this->addFlash('success', 'Administrateur modifié avec succès.');
            return $this->redirectToRoute('admin_administrateur_index');
        }

        return $this->render('Admin/gestion_admin/editAdmin.html.twig', [
            'form' => $form->createView(),
            'administrateur' => $administrateur,
        ]);
    }

    #[Route('/{id}/supprimer', name: 'admin_administrateur_delete', methods: ['POST'])]
    public function delete(Administrateur $administrateur, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $administrateur->getId(), $request->request->get('_token'))) {
            $em->remove($administrateur);
            $em->flush();
            $this->addFlash('success', 'Administrateur supprimé.');
        }

        return $this->redirectToRoute('admin_administrateur_index');
    }
}
