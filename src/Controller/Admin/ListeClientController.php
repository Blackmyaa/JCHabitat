<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/clients')]
class ListeClientController extends AbstractController
{
    #[Route('/', name: 'admin_client_index')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $search = trim((string) $request->query->get('search', ''));

        $qb = $em->getRepository(Client::class)->createQueryBuilder('c');

        // Recherche par nom, prénom ou email
        if ($search !== '') {
            $qb->andWhere('c.nom LIKE :search OR c.prenom LIKE :search OR c.email LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        // Tri par nom alphabétique
        $qb->orderBy('c.nom', 'ASC');

        $clients = $qb->getQuery()->getResult();

        return $this->render('Admin/client/listeClient.html.twig', [
            'clients' => $clients,
            'search' => $search,
        ]);
    }
}
