<?php

namespace App\Repository;

use App\Entity\DevisLigne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DevisLigneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DevisLigne::class);
    }

    // 🔹 Exemples de méthodes personnalisées si besoin
    public function findByDevisId(int $devisId): array
    {
        return $this->createQueryBuilder('dl')
                    ->andWhere('dl.devis = :devisId')
                    ->setParameter('devisId', $devisId)
                    ->getQuery()
                    ->getResult();
    }

    public function findByClient(int $clientId): array
    {
        return $this->createQueryBuilder('d')
                    ->andWhere('d.client = :clientId')
                    ->setParameter('clientId', $clientId)
                    ->orderBy('d.dateCreation', 'DESC')
                    ->getQuery()
                    ->getResult();
    }
}