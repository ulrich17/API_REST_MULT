<?php

namespace App\Entity;

use App\Repository\SinistreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SinistreRepository::class)]
#[ORM\Table(name: 'sinistres')]
#[ORM\UniqueConstraint(
    name: 'unique_sinistre', 
    columns: ['id_vehicule', 'date_sinistre', 'description'])]
class Sinistre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idVehicule = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateSinistre = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $evaluationDegat = null;

    #[ORM\Column]
    private ?float $montantEstime = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\Column(length: 255)]
    private ?string $observations = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdVehicule(): ?int
    {
        return $this->idVehicule;
    }

    public function setIdVehicule(int $idVehicule): static
    {
        $this->idVehicule = $idVehicule;

        return $this;
    }

    public function getDateSinistre(): ?\DateTime
    {
        return $this->dateSinistre;
    }

    public function setDateSinistre(\DateTime $dateSinistre): static
    {
        $this->dateSinistre = $dateSinistre;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

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

    public function getEvaluationDegat(): ?string
    {
        return $this->evaluationDegat;
    }

    public function setEvaluationDegat(string $evaluationDegat): static
    {
        $this->evaluationDegat = $evaluationDegat;

        return $this;
    }

    public function getMontantEstime(): ?float
    {
        return $this->montantEstime;
    }

    public function setMontantEstime(float $montantEstime): static
    {
        $this->montantEstime = $montantEstime;

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
