<?php

namespace App\Entity;

use App\Repository\SnapshotItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SnapshotItemRepository::class)]
class SnapshotItem
{
    #[ORM\Id]
    #[ORM\ManyToOne]
    private Snapshot $snapshot;

    #[ORM\Id]
    #[ORM\ManyToOne]
    private Artifact $artifact;

    #[ORM\ManyToOne]
    private FileObject $fileObject;

    public function getSnapshot(): Snapshot
    {
        return $this->snapshot;
    }

    public function setSnapshot(Snapshot $snapshot): void
    {
        $this->snapshot = $snapshot;
    }

    public function getArtifact(): Artifact
    {
        return $this->artifact;
    }

    public function setArtifact(Artifact $artifact): void
    {
        $this->artifact = $artifact;
    }

    public function getFileObject(): FileObject
    {
        return $this->fileObject;
    }

    public function setFileObject(FileObject $fileObject): void
    {
        $this->fileObject = $fileObject;
    }


}
