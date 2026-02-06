<?php

namespace App\Entity;

use App\Repository\CertificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CertificationRepository::class)]
class Certification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $titre;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column]
    private \DateTimeImmutable $dateObtention;

    #[ORM\ManyToMany(targetEntity: Candidat::class, inversedBy: 'certifications')]
    #[ORM\JoinTable(name: 'candidat_certification')]
    private Collection $candidats;

    public function __construct()
    {
        $this->candidats = new ArrayCollection();
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDateObtention(): \DateTimeImmutable
    {
        return $this->dateObtention;
    }

    public function setDateObtention(\DateTimeImmutable $dateObtention): void
    {
        $this->dateObtention = $dateObtention;
    }

    public function getCandidats(): Collection
    {
        return $this->candidats;
    }

    public function setCandidats(Collection $candidats): void
    {
        $this->candidats = $candidats;
    }


}
