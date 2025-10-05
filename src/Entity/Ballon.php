<?php

namespace App\Entity;

use App\Repository\BallonRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: BallonRepository::class)]
class Ballon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $marque;

    #[ORM\Column(length: 100)]
    private string $modele;

    #[ORM\Column]
    private int $capaciteLitres;

    #[ORM\Column(length: 50)]
    private string $typeEnergie;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $prixHt;

    #[ORM\OneToMany(mappedBy: 'ballon', targetEntity: DevisBallon::class, orphanRemoval: true)]
    private Collection $devisBallons;

    public function __construct()
    {
        $this->devisBallons = new ArrayCollection();
    }
}
