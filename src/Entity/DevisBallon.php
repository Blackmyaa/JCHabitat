<?php

namespace App\Entity;

use App\Repository\DevisBallonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisBallonRepository::class)]
class DevisBallon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'devisBallons')]
    #[ORM\JoinColumn(nullable: false)]
    private Devis $devis;

    #[ORM\ManyToOne(inversedBy: 'devisBallons')]
    #[ORM\JoinColumn(nullable: false)]
    private Ballon $ballon;

    #[ORM\Column]
    private int $quantite;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $prixBallon;
}
