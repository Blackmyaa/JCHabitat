<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private string $numeroDevis;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dateCreation;

    #[ORM\Column(length: 20)]
    private string $statut;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $montantTotal;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private Client $client;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private Administrateur $administrateur;

    #[ORM\OneToMany(mappedBy: 'devis', targetEntity: DevisBallon::class, cascade: ['persist', 'remove'])]
    private Collection $devisBallons;

    #[ORM\OneToMany(mappedBy: 'devis', targetEntity: DevisPrestation::class, cascade: ['persist', 'remove'])]
    private Collection $devisPrestations;

    public function __construct()
    {
        $this->dateCreation = new \DateTimeImmutable();
        $this->devisBallons = new ArrayCollection();
        $this->devisPrestations = new ArrayCollection();
    }
}
