<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DemandeDeDevisRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\NotificationAdminRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin')]
class NotificationController extends AbstractController
{
    #[Route('/notification', name: 'admin_notifications_index', methods: ['GET'])]
    public function index(NotificationAdminRepository $repo): Response
    {
        // On récupère les notifications par date décroissante
        $notifications = $repo->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/notification/listeNotif.html.twig', [
            'notifications' => $notifications,
        ]);
    }

    #[Route('/view/{id}', name: 'admin_notifications_view', methods: ['GET'])]
    public function view(
    int $id,
    NotificationAdminRepository $notifRepo,
    DemandeDeDevisRepository $demandeRepo,
    EntityManagerInterface $em
    ): Response {
        // 🔹 1. Récupérer la notification
        $notif = $notifRepo->find($id);
        if (!$notif) {
            throw $this->createNotFoundException('Notification introuvable.');
        }

        // 🔹 2. Marquer comme lue si nécessaire
        if (!$notif->isRead()) {
            $notif->setIsRead(true);
            $em->flush();
        }

        // 🔹 3. Récupérer la demande associée
        // On passe toujours par le repo pour être sûr d’avoir un objet complet
        $demande = null;
        if ($notif->getDemande() !== null) {
            $demandeId = $notif->getDemande()->getId();
            $demande = $demandeRepo->find($demandeId);
        }

        if (!$demande) {
            $this->addFlash('error', 'Aucune demande de devis associée à cette notification.');
        }

        // 🔹 4. Afficher la page
        return $this->render('admin/notification/view.html.twig', [
            'notification' => $notif,
            'demande' => $demande,
        ]);
    }

    #[Route('/mark-read/{id}', name: 'admin_notifications_mark_read', methods: ['POST'])]
    public function markRead(
        int $id,
        NotificationAdminRepository $repo,
        EntityManagerInterface $em
    ): Response {
        $notif = $repo->find($id);
        if (!$notif) {
            throw $this->createNotFoundException('Notification introuvable.');
        }

        $notif->setIsRead(true);
        $em->flush();

        return $this->redirectToRoute('admin_notifications_index');
    }

    #[Route('/mark-unread/{id}', name: 'admin_notifications_mark_unread', methods: ['POST'])]
    public function markUnread(int $id, NotificationAdminRepository $repo, EntityManagerInterface $em): Response
    {
        $notif = $repo->find($id);
        if ($notif) {
            $notif->setIsRead(false);
            $em->flush();
        }
        return $this->redirectToRoute('admin_notifications_index');
    }

    #[Route('/mark-all-read', name: 'admin_notifications_mark_all', methods: ['POST'])]
    public function markAllRead(
        NotificationAdminRepository $repo,
        EntityManagerInterface $em
    ): Response {
        $notifs = $repo->findBy(['isRead' => false]);

        foreach ($notifs as $notif) {
            $notif->setIsRead(true);
        }

        $em->flush();

        return $this->redirectToRoute('admin_notifications_index');
    }
}
