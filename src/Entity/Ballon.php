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
     * Get the value of marque
     */ 
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * Set the value of marque
     *
     * @return  self
     */ 
    public function setMarque($marque)
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * Get the value of modele
     */ 
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * Set the value of modele
     *
     * @return  self
     */ 
    public function setModele($modele)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get the value of capaciteLitres
     */ 
    public function getCapaciteLitres()
    {
        return $this->capaciteLitres;
    }

    /**
     * Set the value of capaciteLitres
     *
     * @return  self
     */ 
    public function setCapaciteLitres($capaciteLitres)
    {
        $this->capaciteLitres = $capaciteLitres;

        return $this;
    }

    /**
     * Get the value of typeEnergie
     */ 
    public function getTypeEnergie()
    {
        return $this->typeEnergie;
    }

    /**
     * Set the value of typeEnergie
     *
     * @return  self
     */ 
    public function setTypeEnergie($typeEnergie)
    {
        $this->typeEnergie = $typeEnergie;

        return $this;
    }

    /**
     * Get the value of prixHt
     */ 
    public function getPrixHt()
    {
        return $this->prixHt;
    }

    /**
     * Set the value of prixHt
     *
     * @return  self
     */ 
    public function setPrixHt($prixHt)
    {
        $this->prixHt = $prixHt;

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
}
