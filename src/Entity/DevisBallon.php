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

    /**
     * Get the value of prixBallon
     */ 
    public function getPrixBallon()
    {
        return $this->prixBallon;
    }

    /**
     * Set the value of prixBallon
     *
     * @return  self
     */ 
    public function setPrixBallon($prixBallon)
    {
        $this->prixBallon = $prixBallon;

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
     * Get the value of ballon
     */ 
    public function getBallon()
    {
        return $this->ballon;
    }

    /**
     * Set the value of ballon
     *
     * @return  self
     */ 
    public function setBallon($ballon)
    {
        $this->ballon = $ballon;

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
}
