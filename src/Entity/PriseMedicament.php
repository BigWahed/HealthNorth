<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entite PriseMedicament :
 * represente la maniere de prendre un medicament pour un patient.
 */
#[ORM\Entity]
class PriseMedicament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $posologie = null;

    #[ORM\Column(length: 255)]
    private ?string $frequence = null;

    #[ORM\Column(length: 255)]
    private ?string $momentPrise = null;

    /**
     * Patient qui suit la prise du medicament.
     */
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $patient = null;

    #[ORM\ManyToOne(targetEntity: Medicament::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Medicament $medicament = null;

    #[ORM\ManyToOne(targetEntity: Prescription::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prescription $prescription = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosologie(): ?string
    {
        return $this->posologie;
    }

    public function setPosologie(string $posologie): static
    {
        $this->posologie = $posologie;

        return $this;
    }

    public function getFrequence(): ?string
    {
        return $this->frequence;
    }

    public function setFrequence(string $frequence): static
    {
        $this->frequence = $frequence;

        return $this;
    }

    public function getMomentPrise(): ?string
    {
        return $this->momentPrise;
    }

    public function setMomentPrise(string $momentPrise): static
    {
        $this->momentPrise = $momentPrise;

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

    public function getMedicament(): ?Medicament
    {
        return $this->medicament;
    }

    public function setMedicament(?Medicament $medicament): static
    {
        $this->medicament = $medicament;

        return $this;
    }

    public function getPrescription(): ?Prescription
    {
        return $this->prescription;
    }

    public function setPrescription(?Prescription $prescription): static
    {
        $this->prescription = $prescription;

        return $this;
    }
}

