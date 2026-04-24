<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entite Prescription :
 * ordonnance redigee par un professionnel pour un patient.
 */
#[ORM\Entity]
class Prescription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'date_immutable')]
    private ?\DateTimeImmutable $datePrescription = null;

    #[ORM\Column(type: 'text')]
    private ?string $contenu = null;

    /**
     * Patient concerne par la prescription.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $patient = null;

    /**
     * Professionnel qui redige la prescription.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $professionnel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePrescription(): ?\DateTimeImmutable
    {
        return $this->datePrescription;
    }

    public function setDatePrescription(\DateTimeImmutable $datePrescription): static
    {
        $this->datePrescription = $datePrescription;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

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

    public function getProfessionnel(): ?User
    {
        return $this->professionnel;
    }

    public function setProfessionnel(?User $professionnel): static
    {
        $this->professionnel = $professionnel;

        return $this;
    }
}

