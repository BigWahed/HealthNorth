<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entite ResultatAnalyse :
 * stocke un resultat d'examen medical pour un patient.
 */
#[ORM\Entity]
class ResultatAnalyse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $titre = null;

    #[ORM\Column(length: 100)]
    private ?string $typeAnalyse = null;

    #[ORM\Column(type: 'date_immutable')]
    private ?\DateTimeImmutable $dateAnalyse = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentaire = null;

    /**
     * Patient auquel appartient ce resultat.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $patient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getTypeAnalyse(): ?string
    {
        return $this->typeAnalyse;
    }

    public function setTypeAnalyse(string $typeAnalyse): static
    {
        $this->typeAnalyse = $typeAnalyse;

        return $this;
    }

    public function getDateAnalyse(): ?\DateTimeImmutable
    {
        return $this->dateAnalyse;
    }

    public function setDateAnalyse(\DateTimeImmutable $dateAnalyse): static
    {
        $this->dateAnalyse = $dateAnalyse;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): static
    {
        $this->patient = $patient;

        return $this;
    }
}

