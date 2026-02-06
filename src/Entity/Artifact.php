<?php

namespace App\Entity;

use App\Repository\ArtifactRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArtifactRepository::class)]
class Artifact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private Track $track;

    #[ORM\Column]
    private string $artifactName;

    #[ORM\Column(type: 'text')]
    private string $artifactDescription;

    #[ORM\Column]
    private string $artifactType;

    #[ORM\Column(nullable: true)]
    private ?string $language = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $testContent = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    //#[ORM\OneToMany(mappedBy: 'artifact', targetEntity: FileObject::class)]
    //private Collection $files;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTrack(): Track
    {
        return $this->track;
    }

    public function setTrack(Track $track): void
    {
        $this->track = $track;
    }

    public function getArtifactName(): string
    {
        return $this->artifactName;
    }

    public function setArtifactName(string $artifactName): void
    {
        $this->artifactName = $artifactName;
    }

    public function getArtifactDescription(): string
    {
        return $this->artifactDescription;
    }

    public function setArtifactDescription(string $artifactDescription): void
    {
        $this->artifactDescription = $artifactDescription;
    }

    public function getArtifactType(): string
    {
        return $this->artifactType;
    }

    public function setArtifactType(string $artifactType): void
    {
        $this->artifactType = $artifactType;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): void
    {
        $this->language = $language;
    }

    public function getTestContent(): ?string
    {
        return $this->testContent;
    }

    public function setTestContent(?string $testContent): void
    {
        $this->testContent = $testContent;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function setFiles(Collection $files): void
    {
        $this->files = $files;
    }



}
