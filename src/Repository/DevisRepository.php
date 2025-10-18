<?php

namespace App\Repository;

use App\Entity\Devis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Devis>
 *
 * @method Devis? find($id, $lockMode = null, $lockVersion = null)
 * @method Devis? findOneBy(array $criteria, array $orderBy = null)
 * @method Devis[]    findAll()
 * @method Devis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Devis::class);
    }

    // Ajoutez ici vos méthodes personnalisées si nécessaire

    //Trouvé les devis nouveau et en cours
    public function findActiveDevis(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.statut IN (:statuts)')
            ->setParameter('statuts', ['nouveau', 'en_cours'])
            ->orderBy('d.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }


    //afficher uniquement les nouveaux devis
    public function findNewDevis(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.statut IN (:statuts)')
            ->setParameter('statuts', 'nouveau')
            ->orderBy('d.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //afficher les devis annulés
    public function findCanceledDevis(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.statut IN (:statuts)')
            ->setParameter('statuts', 'annulé')
            ->orderBy('d.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();

    }
}
