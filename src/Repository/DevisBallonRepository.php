<?php

namespace App\Repository;

use App\Entity\DevisBallon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DevisBallon>
 *
 * @method DevisBallon? find($id, $lockMode = null, $lockVersion = null)
 * @method DevisBallon? findOneBy(array $criteria, array $orderBy = null)
 * @method DevisBallon[]    findAll()
 * @method DevisBallon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisBallonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DevisBallon::class);
    }

    // Ajoutez ici vos méthodes personnalisées si nécessaire
}
