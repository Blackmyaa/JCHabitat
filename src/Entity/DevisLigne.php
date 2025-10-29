<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DevisLigneRepository;

#[ORM\Entity(repositoryClass: DevisLigneRepository::class)]
class DevisLigne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(targetEntity: Devis::class, inversedBy: 'lignes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Devis $devis = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantity = 1;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $unitPrice = 0.0;

    public function getId(): ?int { return $this->id; }

    public function getDevis(): ?Devis { return $this->devis; }
    public function setDevis(?Devis $devis): self { $this->devis = $devis; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }

    public function getQuantity(): ?int { return $this->quantity; }
    public function setQuantity(int $quantity): self { $this->quantity = $quantity; return $this; }

    public function getUnitPrice(): ?float { return $this->unitPrice; }
    public function setUnitPrice(float $unitPrice): self { $this->unitPrice = $unitPrice; return $this; }

    public function getTotal(): float { return $this->quantity * $this->unitPrice; }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
}
