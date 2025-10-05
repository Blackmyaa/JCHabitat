<?php

namespace App\Repository;

use App\Entity\Ballon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ballon>
 *
 * @method Ballon? find($id, $lockMode = null, $lockVersion = null)
 * @method Ballon? findOneBy(array $criteria, array $orderBy = null)
 * @method Ballon[]    findAll()
 * @method Ballon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BallonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ballon::class);
    }

    // Ajoutez ici vos méthodes personnalisées si nécessaire
}
