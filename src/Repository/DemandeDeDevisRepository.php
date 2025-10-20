<?php

namespace App\Repository;

use App\Entity\DemandeDeDevis;
use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandeDeDevis>
 */
class DemandeDeDevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeDeDevis::class);
    }

    /**
     * Récupère toutes les demandes triées par date décroissante.
     */
    public function findAllOrderedByDate(): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.dateDemande', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les demandes associées à un client spécifique.
     */
    public function findByClient(Client $client): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.client = :client')
            ->setParameter('client', $client)
            ->orderBy('d.dateDemande', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche une demande selon un mot-clé (nom, prénom, email ou ville)
     */
    public function search(string $keyword): array
    {
        return $this->createQueryBuilder('d')
            ->where('LOWER(d.nom) LIKE :kw')
            ->orWhere('LOWER(d.prenom) LIKE :kw')
            ->orWhere('LOWER(d.email) LIKE :kw')
            ->orWhere('LOWER(d.ville) LIKE :kw')
            ->setParameter('kw', '%' . strtolower($keyword) . '%')
            ->orderBy('d.dateDemande', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Filtre les demandes selon un intervalle de dates.
     */
    public function findBetweenDates(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.dateDemande BETWEEN :start AND :end')
            ->setParameter('start', $start->format('Y-m-d 00:00:00'))
            ->setParameter('end', $end->format('Y-m-d 23:59:59'))
            ->orderBy('d.dateDemande', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
