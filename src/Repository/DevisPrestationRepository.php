<?php

namespace App\Repository;

use App\Entity\DevisPrestation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DevisPrestation>
 *
 * @method DevisPrestation? find($id, $lockMode = null, $lockVersion = null)
 * @method DevisPrestation? findOneBy(array $criteria, array $orderBy = null)
 * @method DevisPrestation[]    findAll()
 * @method DevisPrestation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisPrestationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DevisPrestation::class);
    }

    // Ajoutez ici vos méthodes personnalisées si nécessaire
}
