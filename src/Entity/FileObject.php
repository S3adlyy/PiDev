<?php

namespace App\Entity;

use App\Repository\FileObjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileObjectRepository::class)]
class FileObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'files')]
    private Artifact $artifact;

    #[ORM\Column]
    private string $storageKey;

    #[ORM\Column]
    private string $publicUrl;

    #[ORM\Column]
    private string $mimeType;

    #[ORM\Column]
    private int $fileSize;

    #[ORM\Column]
    private \DateTimeImmutable $uploadedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getArtifact(): Artifact
    {
        return $this->artifact;
    }

    public function setArtifact(Artifact $artifact): void
    {
        $this->artifact = $artifact;
    }

    public function getStorageKey(): string
    {
        return $this->storageKey;
    }

    public function setStorageKey(string $storageKey): void
    {
        $this->storageKey = $storageKey;
    }

    public function getPublicUrl(): string
    {
        return $this->publicUrl;
    }

    public function setPublicUrl(string $publicUrl): void
    {
        $this->publicUrl = $publicUrl;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    public function setFileSize(int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    public function getUploadedAt(): \DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTimeImmutable $uploadedAt): void
    {
        $this->uploadedAt = $uploadedAt;
    }


}
