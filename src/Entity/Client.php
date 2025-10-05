<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $nom;

    #[ORM\Column(length: 100)]
    private string $prenom;

    #[ORM\Column(length: 255)]
    private string $adresse;

    #[ORM\Column(length: 10)]
    private string $codePostal;

    #[ORM\Column(length: 100)]
    private string $ville;

    #[ORM\Column(length: 20)]
    private string $telephone;

    #[ORM\Column(length: 150)]
    private string $email;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Devis::class, orphanRemoval: true)]
    private Collection $devis;

    public function __construct()
    {
        $this->devis = new ArrayCollection();
    }
}
