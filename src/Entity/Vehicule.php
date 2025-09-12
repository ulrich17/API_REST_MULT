<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\CategorieVehicule;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
#[UniqueEntity(
    fields: ['immatriculation'], 
    message: 'Ce véhicule existe déjà'
)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['vehicule:list', 'vehicule:detail', 'categorieVehicule:detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['vehicule:list', 'vehicule:detail', 'categorieVehicule:detail'])]
    #[Assert\Regex(
        pattern: '/^[A-HJ-NP-TV-Z]{2}-\d{3}-[A-HJ-NP-TV-Z]{2}$/',
        message: 'Format d\'immatriculation incorrect.'
    )]
    private ?string $immatriculation = null;

    #[ORM\Column(length: 255)]
    #[Groups(['vehicule:list', 'vehicule:detail', 'categorieVehicule:detail'])]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    #[Groups(['vehicule:list', 'vehicule:detail', 'categorieVehicule:detail'])]
    private ?string $modele = null;

    #[ORM\Column]
    #[Groups(['vehicule:list', 'vehicule:detail', 'categorieVehicule:detail'])]
    private ?int $annee = null;

    #[ORM\Column(length: 255)]
    #[Groups(['vehicule:list', 'vehicule:detail', 'categorieVehicule:detail'])]
    private ?string $couleur = null;

    #[ORM\Column(length: 255)]
    #[Groups(['vehicule:list', 'vehicule:detail', 'categorieVehicule:detail'])]
    private ?string $etat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['vehicule:list', 'vehicule:detail', 'categorieVehicule:detail'])]
    private ?\DateTime $dateEntree = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['vehicule:list', 'vehicule:detail', 'categorieVehicule:detail'])]
    private ?\DateTime $dateSortie = null;

    #[ORM\Column(length: 255)]
    #[Groups(['vehicule:list', 'vehicule:detail', 'categorieVehicule:detail'])]
    private ?string $observations = null;

    #[ORM\ManyToOne(targetEntity: CategorieVehicule::class, inversedBy: 'vehicules')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['vehicule:list', 'vehicule:detail'])]
    private ?CategorieVehicule $categorieVehicule = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(string $immatriculation): static
    {
        $this->immatriculation = mb_strtoupper(str_replace([' ', '-'], '', $immatriculation));

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateEntree(): ?\DateTime
    {
        return $this->dateEntree;
    }

    public function setDateEntree(\DateTime $dateEntree): static
    {
        $this->dateEntree = $dateEntree;

        return $this;
    }

    public function getDateSortie(): ?\DateTime
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTime $dateSortie): static
    {
        $this->dateSortie = $dateSortie;

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

    public function getCategorieVehicule(): ?CategorieVehicule
    {
        return $this->categorieVehicule;
    }

    public function setCategorieVehicule(?CategorieVehicule $categorieVehicule): static
    {
        $this->categorieVehicule = $categorieVehicule;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}
