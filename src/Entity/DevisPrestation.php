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

    

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of devis
     */ 
    public function getDevis()
    {
        return $this->devis;
    }

    /**
     * Set the value of devis
     *
     * @return  self
     */ 
    public function setDevis($devis)
    {
        $this->devis = $devis;

        return $this;
    }

    /**
     * Get the value of prestation
     */ 
    public function getPrestation()
    {
        return $this->prestation;
    }

    /**
     * Set the value of prestation
     *
     * @return  self
     */ 
    public function setPrestation($prestation)
    {
        $this->prestation = $prestation;

        return $this;
    }

    /**
     * Get the value of quantite
     */ 
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set the value of quantite
     *
     * @return  self
     */ 
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get the value of prixPrestation
     */ 
    public function getPrixPrestation()
    {
        return $this->prixPrestation;
    }

    /**
     * Set the value of prixPrestation
     *
     * @return  self
     */ 
    public function setPrixPrestation($prixPrestation)
    {
        $this->prixPrestation = $prixPrestation;

        return $this;
    }
}
