<?php

namespace App\Controller\Admin;

use App\Form\AddAdminType;
use App\Entity\Administrateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
class NewAdminController extends AbstractController
{
    #[Route('/new-admin', name: 'app_new_admin')]
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
        
        
        return $this->render('Admin/new_admin/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
