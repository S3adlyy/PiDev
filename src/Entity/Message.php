<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private Conversation $conversation;

    #[ORM\ManyToOne]
    private User $expediteur;

    #[ORM\ManyToOne]
    private User $destinataire;

    #[ORM\Column(type: 'text')]
    private string $contenu;

    #[ORM\Column]
    private \DateTimeImmutable $dateEnvoi;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateModification = null;

    #[ORM\Column]
    private string $statut;

    #[ORM\Column]
    private string $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getConversation(): Conversation
    {
        return $this->conversation;
    }

    public function setConversation(Conversation $conversation): void
    {
        $this->conversation = $conversation;
    }

    public function getExpediteur(): User
    {
        return $this->expediteur;
    }

    public function setExpediteur(User $expediteur): void
    {
        $this->expediteur = $expediteur;
    }

    public function getDestinataire(): User
    {
        return $this->destinataire;
    }

    public function setDestinataire(User $destinataire): void
    {
        $this->destinataire = $destinataire;
    }

    public function getContenu(): string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): void
    {
        $this->contenu = $contenu;
    }

    public function getDateEnvoi(): \DateTimeImmutable
    {
        return $this->dateEnvoi;
    }

    public function setDateEnvoi(\DateTimeImmutable $dateEnvoi): void
    {
        $this->dateEnvoi = $dateEnvoi;
    }

    public function getDateModification(): ?\DateTimeImmutable
    {
        return $this->dateModification;
    }

    public function setDateModification(?\DateTimeImmutable $dateModification): void
    {
        $this->dateModification = $dateModification;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }



}
