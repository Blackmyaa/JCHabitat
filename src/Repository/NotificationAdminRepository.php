<?php

namespace App\Repository;

use App\Entity\NotificationAdmin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NotificationAdminRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationAdmin::class);
    }

    /**
     * Récupère toutes les notifications non lues (pour affichage dans le dashboard)
     */
    public function findUnread(): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.isRead = :read')
            ->setParameter('read', false)
            ->orderBy('n.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère toutes les notifications triées par date (récentes d’abord)
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Marque une notification comme lue
     */
    public function markAsRead(NotificationAdmin $notification): void
    {
        $notification->setIsRead(true);
        $this->_em->flush();
    }

    /**
     * Marque toutes les notifications comme lues
     */
    public function markAllAsRead(): void
    {
        $this->createQueryBuilder('n')
            ->update()
            ->set('n.isRead', ':read')
            ->setParameter('read', true)
            ->getQuery()
            ->execute();
    }

    /**
     * Compte le nombre de notifications non lues
     */
    public function countUnread(): int
    {
        return (int) $this->createQueryBuilder('n')
            ->select('COUNT(n.id)')
            ->andWhere('n.isRead = :read')
            ->setParameter('read', false)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
