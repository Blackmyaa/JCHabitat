<?php

namespace App\Entity;

use App\Entity\DevisLigne;
use App\Entity\DevisBallon;
use App\Entity\DemandeDeDevis;
use App\Entity\DevisPrestation;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DevisRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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

    #[ORM\ManyToOne(targetEntity: DemandeDeDevis::class, inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?DemandeDeDevis $demande = null;
    
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

    #[ORM\Column(type: 'boolean')]
    private bool $isRead = false;

    // 🔹 Relation avec les lignes
    #[ORM\OneToMany(mappedBy: 'devis', targetEntity: DevisLigne::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $lignes;

    public function __construct()
    {
        $this->dateCreation = new \DateTimeImmutable();
        $this->lignes = new ArrayCollection();
        $this->devisBallons = new ArrayCollection();
        $this->devisPrestations = new ArrayCollection();
    }

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;
        return $this;
    }

    /**
     * Get the value of numeroDevis
     */ 
    public function getNumeroDevis()
    {
        return $this->numeroDevis;
    }

    /**
     * Set the value of numeroDevis
     *
     * @return  self
     */ 
    public function setNumeroDevis($numeroDevis)
    {
        $this->numeroDevis = $numeroDevis;

        return $this;
    }

    /**
     * Get the value of statut
     */ 
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set the value of statut
     *
     * @return  self
     */ 
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get the value of montantTotal
     */ 
    public function getMontantTotal()
    {
        return $this->montantTotal;
    }

    /**
     * Set the value of montantTotal
     *
     * @return  self
     */ 
    public function setMontantTotal($montantTotal)
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }

    /**
     * Get the value of client
     */ 
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set the value of client
     *
     * @return  self
     */ 
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get the value of administrateur
     */ 
    public function getAdministrateur()
    {
        return $this->administrateur;
    }

    /**
     * Set the value of administrateur
     *
     * @return  self
     */ 
    public function setAdministrateur($administrateur)
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    /**
     * Get the value of devisBallons
     */ 
    public function getDevisBallons()
    {
        return $this->devisBallons;
    }

    /**
     * Set the value of devisBallons
     *
     * @return  self
     */ 
    public function setDevisBallons($devisBallons)
    {
        $this->devisBallons = $devisBallons;

        return $this;
    }

    /**
     * Get the value of devisPrestations
     */ 
    public function getDevisPrestations()
    {
        return $this->devisPrestations;
    }

    /**
     * Set the value of devisPrestations
     *
     * @return  self
     */ 
    public function setDevisPrestations($devisPrestations)
    {
        $this->devisPrestations = $devisPrestations;

        return $this;
    }

    /**
     * Get the value of dateCreation
     */ 
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set the value of dateCreation
     *
     * @return  self
     */ 
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get the value of demande
     */ 
    public function getDemande()
    {
        return $this->demande;
    }

    /**
     * Set the value of demande
     *
     * @return  self
     */ 
    public function setDemande($demande)
    {
        $this->demande = $demande;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Collection|DevisLigne[]
     */
    public function getLignes(): Collection { return $this->lignes; }

    public function addLigne(DevisLigne $ligne): self
    {
        if (!$this->lignes->contains($ligne)) {
            $this->lignes->add($ligne);
            $ligne->setDevis($this);
        }
        return $this;
    }

    public function removeLigne(DevisLigne $ligne): self
    {
        if ($this->lignes->removeElement($ligne)) {
            if ($ligne->getDevis() === $this) {
                $ligne->setDevis(null);
            }
        }
        return $this;
    }
}
