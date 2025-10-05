<?php

namespace App\Entity;

use App\Repository\DevisPrestationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisPrestationRepository::class)]
class DevisPrestation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'devisPrestations')]
    #[ORM\JoinColumn(nullable: false)]
    private Devis $devis;

    #[ORM\ManyToOne(inversedBy: 'devisPrestations')]
    #[ORM\JoinColumn(nullable: false)]
    private Prestation $prestation;

    #[ORM\Column]
    private int $quantite;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $prixPrestation;
}
