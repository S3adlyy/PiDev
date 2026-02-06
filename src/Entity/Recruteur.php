<?php

namespace App\Entity;

use App\Repository\RecruteurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecruteurRepository::class)]
class Recruteur extends User
{

    #[ORM\Column]
    private string $orgName;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?string $websiteUrl = null;

    #[ORM\Column(nullable: true)]
    private ?string $logoUrl = null;

    #[ORM\Column(nullable: true)]
    private ?string $profilePic = null;

    //#[ORM\OneToMany(mappedBy: 'recruteur', targetEntity: OffreEmploi::class)]
    //private Collection $offres;



    public function getOrgName(): ?string
    {
        return $this->orgName;
    }

    public function setOrgName(string $orgName): static
    {
        $this->orgName = $orgName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl(string $websiteUrl): static
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    public function getLogoUrl(): ?string
    {
        return $this->logoUrl;
    }

    public function setLogoUrl(string $logoUrl): static
    {
        $this->logoUrl = $logoUrl;

        return $this;
    }

    public function getProfilePic(): ?string
    {
        return $this->profilePic;
    }

    public function setProfilePic(string $profilePic): static
    {
        $this->profilePic = $profilePic;

        return $this;
    }
}
