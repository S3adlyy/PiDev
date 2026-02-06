<?php

namespace App\Entity;

use App\Repository\OffreEmploiRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreEmploiRepository::class)]
class OffreEmploi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'offres')]
    private Recruteur $recruteur;

    #[ORM\Column]
    private string $titre;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column]
    private float $salaire;

    #[ORM\Column]
    private string $typeContrat;

    #[ORM\Column]
    private string $localisation;

    #[ORM\Column]
    private \DateTimeImmutable $datePublication;

    #[ORM\Column]
    private \DateTimeImmutable $dateExpiration;

    #[ORM\Column]
    private string $niveauQualification;

    #[ORM\Column]
    private int $experienceRequise;

    #[ORM\Column(type: 'json')]
    private array $competencesRequises;

    #[ORM\Column]
    private string $secteurActivite;

    #[ORM\Column]
    private string $entreprise;

    #[ORM\Column]
    private string $contactRecruteur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getRecruteur(): Recruteur
    {
        return $this->recruteur;
    }

    public function setRecruteur(Recruteur $recruteur): void
    {
        $this->recruteur = $recruteur;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getSalaire(): float
    {
        return $this->salaire;
    }

    public function setSalaire(float $salaire): void
    {
        $this->salaire = $salaire;
    }

    public function getTypeContrat(): string
    {
        return $this->typeContrat;
    }

    public function setTypeContrat(string $typeContrat): void
    {
        $this->typeContrat = $typeContrat;
    }

    public function getLocalisation(): string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): void
    {
        $this->localisation = $localisation;
    }

    public function getDatePublication(): \DateTimeImmutable
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTimeImmutable $datePublication): void
    {
        $this->datePublication = $datePublication;
    }

    public function getDateExpiration(): \DateTimeImmutable
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(\DateTimeImmutable $dateExpiration): void
    {
        $this->dateExpiration = $dateExpiration;
    }

    public function getNiveauQualification(): string
    {
        return $this->niveauQualification;
    }

    public function setNiveauQualification(string $niveauQualification): void
    {
        $this->niveauQualification = $niveauQualification;
    }

    public function getExperienceRequise(): int
    {
        return $this->experienceRequise;
    }

    public function setExperienceRequise(int $experienceRequise): void
    {
        $this->experienceRequise = $experienceRequise;
    }

    public function getCompetencesRequises(): array
    {
        return $this->competencesRequises;
    }

    public function setCompetencesRequises(array $competencesRequises): void
    {
        $this->competencesRequises = $competencesRequises;
    }

    public function getSecteurActivite(): string
    {
        return $this->secteurActivite;
    }

    public function setSecteurActivite(string $secteurActivite): void
    {
        $this->secteurActivite = $secteurActivite;
    }

    public function getEntreprise(): string
    {
        return $this->entreprise;
    }

    public function setEntreprise(string $entreprise): void
    {
        $this->entreprise = $entreprise;
    }

    public function getContactRecruteur(): string
    {
        return $this->contactRecruteur;
    }

    public function setContactRecruteur(string $contactRecruteur): void
    {
        $this->contactRecruteur = $contactRecruteur;
    }


}
