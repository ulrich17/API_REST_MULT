<?php

namespace App\Entity;

use App\Repository\MaintenanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaintenanceRepository::class)]
class Maintenance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idvehicule = null;

    #[ORM\Column(length: 255)]
    private ?string $typemaintenance = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $datePlanifie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateEffectuee = null;

    #[ORM\Column]
    private ?float $cout = null;

    #[ORM\Column]
    private ?int $kilometrage = null;

    #[ORM\Column]
    private ?string $typealerte = null;

    #[ORM\Column(length: 255)]
    private ?string $observations = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdvehicule(): ?int
    {
        return $this->idvehicule;
    }

    public function setIdvehicule(int $idvehicule): static
    {
        $this->idvehicule = $idvehicule;

        return $this;
    }

    public function getTypemaintenance(): ?string
    {
        return $this->typemaintenance;
    }

    public function setTypemaintenance(string $typemaintenance): static
    {
        $this->typemaintenance = $typemaintenance;

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

    public function getDatePlanifie(): ?\DateTime
    {
        return $this->datePlanifie;
    }

    public function setDatePlanifie(\DateTime $datePlanifie): static
    {
        $this->datePlanifie = $datePlanifie;

        return $this;
    }

    public function getDateEffectuee(): ?\DateTime
    {
        return $this->dateEffectuee;
    }

    public function setDateEffectuee(\DateTime $dateEffectuee): static
    {
        $this->dateEffectuee = $dateEffectuee;

        return $this;
    }

    public function getCout(): ?float
    {
        return $this->cout;
    }

    public function setCout(float $cout): static
    {
        $this->cout = $cout;

        return $this;
    }

    public function getKilometrage(): ?int
    {
        return $this->kilometrage;
    }

    public function setKilometrage(int $kilometrage): static
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function getTypeAlerte(): ?string
    {
        return $this->typealerte;
    }

    public function setTypeAlerte(string $typealerte): static
    {
        $this->typealerte = $typealerte;

        return $this;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(string $observations): static
    {
        $this->observations = $observations;

        return $this;
    }
}
