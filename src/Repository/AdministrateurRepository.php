<?php

namespace App\Repository;

use App\Entity\Administrateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Administrateur>
 *
 * @method Administrateur? find($id, $lockMode = null, $lockVersion = null)
 * @method Administrateur? findOneBy(array $criteria, array $orderBy = null)
 * @method Administrateur[]    findAll()
 * @method Administrateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdministrateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Administrateur::class);
    }

    // Ajoutez ici vos méthodes personnalisées si nécessaire
}
