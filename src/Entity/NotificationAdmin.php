<?php

namespace App\Entity;

use App\Repository\NotificationAdminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationAdminRepository::class)]
class NotificationAdmin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\Column]
    private ?bool $isRead = false;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    // ✅ Relation avec DemandeDeDevis
    #[ORM\ManyToOne(targetEntity: DemandeDeDevis::class, inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?DemandeDeDevis $demande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): static
    {
        $this->isRead = $isRead;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // 🔗 Getters/setters pour la relation avec DemandeDeDevis
    public function getDemande(): ?DemandeDeDevis
    {
        return $this->demande;
    }

    public function setDemande(?DemandeDeDevis $demande): static
    {
        $this->demande = $demande;
        return $this;
    }
}