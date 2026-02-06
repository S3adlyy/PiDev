<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'candidat' => Candidat::class,
    'recruteur' => Recruteur::class,
    'admin' => Admin::class
])]

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $FirstName = null;

    #[ORM\Column(length: 255)]
    private ?string $LastName = null;

    #[ORM\Column(length: 255)]
    private ?string $Email = null;

    #[ORM\Column(length: 255)]
    private ?string $PasswordHash = null;

    #[ORM\Column(length: 255)]
    private ?string $Roles = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $Last_Login_at = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $CreatedAT = null;

    //#[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Reclamation::class)]
    //protected Collection $reclamations;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): static
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(string $LastName): static
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getPasswordHash(): ?string
    {
        return $this->PasswordHash;
    }

    public function setPasswordHash(string $PasswordHash): static
    {
        $this->PasswordHash = $PasswordHash;

        return $this;
    }

    public function getRoles(): ?string
    {
        return $this->Roles;
    }

    public function setRoles(string $Roles): static
    {
        $this->Roles = $Roles;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getLastLoginAt(): ?\DateTime
    {
        return $this->Last_Login_at;
    }

    public function setLastLoginAt(\DateTime $Last_Login_at): static
    {
        $this->Last_Login_at = $Last_Login_at;

        return $this;
    }

    public function getCreatedAT(): ?\DateTime
    {
        return $this->CreatedAT;
    }

    public function setCreatedAT(\DateTime $CreatedAT): static
    {
        $this->CreatedAT = $CreatedAT;

        return $this;
    }
}
