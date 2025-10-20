<?php

namespace App\Entity;

use App\Entity\Client;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\NotificationAdmin;
use App\Repository\DemandeDeDevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: DemandeDeDevisRepository::class)]
class DemandeDeDevis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'demandes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Client $client = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 20)]
    private ?string $telephone = null;

    #[ORM\Column(length: 150)]
    private ?string $email = null;

    #[ORM\Column(length: 100)]
    private ?string $pays = null;

    #[ORM\Column(length: 150)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $typeIntervention = null;

    #[ORM\Column(length: 100)]
    private ?string $capacite = null;

    #[ORM\Column(length: 255)]
    private ?string $position = null;

    #[ORM\Column(length: 255)]
    private ?string $accessibilite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ancienModele = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $message = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateDemande = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photos = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = 'nouvelle';

    #[ORM\OneToMany(mappedBy: 'demande', targetEntity: NotificationAdmin::class)]
    private Collection $notifications;

    public function __construct()
    {
        $this->dateDemande = new \DateTime();
        $this->notifications = new ArrayCollection();
    }

    // 💡 Getters & Setters
    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return ucfirst(strtolower($this->nom)); }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }

    public function getPrenom(): ?string { return ucfirst(strtolower($this->prenom)); }
    public function setPrenom(string $prenom): self { $this->prenom = $prenom; return $this; }

    public function getTelephone(): ?string { return $this->telephone; }
    public function setTelephone(string $telephone): self { $this->telephone = $telephone; return $this; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }

    public function getPays(): ?string { return $this->pays; }
    public function setPays(string $pays): self { $this->pays = $pays; return $this; }

    public function getVille(): ?string { return $this->ville; }
    public function setVille(string $ville): self { $this->ville = $ville; return $this; }

    public function getTypeIntervention(): ?string { return $this->typeIntervention; }
    public function setTypeIntervention(string $typeIntervention): self { $this->typeIntervention = $typeIntervention; return $this; }

    public function getCapacite(): ?string { return $this->capacite; }
    public function setCapacite(string $capacite): self { $this->capacite = $capacite; return $this; }

    public function getPosition(): ?string { return $this->position; }
    public function setPosition(string $position): self { $this->position = $position; return $this; }

    public function getAccessibilite(): ?string { return $this->accessibilite; }
    public function setAccessibilite(string $accessibilite): self { $this->accessibilite = $accessibilite; return $this; }

    public function getAncienModele(): ?string { return $this->ancienModele; }
    public function setAncienModele(?string $ancienModele): self { $this->ancienModele = $ancienModele; return $this; }

    public function getMessage(): ?string { return $this->message; }
    public function setMessage(?string $message): self { $this->message = $message; return $this; }

    public function getPhotos(): ?string { return $this->photos; }
    public function setPhotos(?string $photos): self { $this->photos = $photos; return $this; }

    public function getDateDemande(): ?\DateTimeInterface { return $this->dateDemande; }

    public function getStatut(): ?string { return $this->statut; }
    public function setStatut(string $statut): self { $this->statut = $statut; return $this; }

    public function getClient(): ?Client { return $this->client; }
    public function setClient(?Client $client): self { $this->client = $client; return $this; }

    public function getNotifications(): Collection
    {
        return $this->notifications;
    }
}
