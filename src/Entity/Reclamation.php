<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private User $utilisateur;

    #[ORM\Column]
    private string $objet;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column]
    private string $categorie;

    #[ORM\Column]
    private \DateTimeImmutable $dateCreation;

    #[ORM\Column]
    private string $statut;

    #[ORM\Column]
    private string $priorite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUtilisateur(): User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(User $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }

    public function getObjet(): string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): void
    {
        $this->objet = $objet;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDateCreation(): \DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    public function getCategorie(): string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): void
    {
        $this->categorie = $categorie;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function getPriorite(): string
    {
        return $this->priorite;
    }

    public function setPriorite(string $priorite): void
    {
        $this->priorite = $priorite;
    }

}
