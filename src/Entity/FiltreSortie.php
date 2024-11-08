<?php

namespace App\Entity;

use App\Repository\FiltreSortieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FiltreSortieRepository::class)]
class FiltreSortie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


//    #[ORM\ManyToOne(targetEntity: Campus::class)]
//    private ?Campus $campus = null;
    #[ORM\Column(length: 255)]
    private ?string $campus = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rechercheParNomDeSortie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRechercheDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRechercheFin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sortiesDeLorganisateur = null;

    #[ORM\Column]
    private ?bool $sortiesDontJeSuisInscrit = null;

    #[ORM\Column]
    private ?bool $sortiesDontJeNeSuisPasInscrit = null;

    #[ORM\Column]
    private ?bool $sortiesPassees = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCampus(): ?string
    {
        return $this->campus;
    }

    public function setCampus(string $campus): static
    {
        $this->campus = $campus;

        return $this;
    }

    public function getRechercheParNomDeSortie(): ?string
    {
        return $this->rechercheParNomDeSortie;
    }

    public function setRechercheParNomDeSortie(?string $rechercheParNomDeSortie): static
    {
        $this->rechercheParNomDeSortie = $rechercheParNomDeSortie;

        return $this;
    }

    public function getDateRechercheDebut(): ?\DateTimeInterface
    {
        return $this->dateRechercheDebut;
    }

    public function setDateRechercheDebut(?\DateTimeInterface $dateRechercheDebut): static
    {
        $this->dateRechercheDebut = $dateRechercheDebut;

        return $this;
    }

    public function getDateRechercheFin(): ?\DateTimeInterface
    {
        return $this->dateRechercheFin;
    }

    public function setDateRechercheFin(?\DateTimeInterface $dateRechercheFin): static
    {
        $this->dateRechercheFin = $dateRechercheFin;

        return $this;
    }

    public function getSortiesDeLorganisateur(): ?string
    {
        return $this->sortiesDeLorganisateur;
    }

    public function setSortiesDeLorganisateur(?string $sortiesDeLorganisateur): static
    {
        $this->sortiesDeLorganisateur = $sortiesDeLorganisateur;

        return $this;
    }

    public function isSortiesDontJeSuisInscrit(): ?bool
    {
        return $this->sortiesDontJeSuisInscrit;
    }

    public function setSortiesDontJeSuisInscrit(bool $sortiesDontJeSuisInscrit): static
    {
        $this->sortiesDontJeSuisInscrit = $sortiesDontJeSuisInscrit;

        return $this;
    }

    public function isSortiesDontJeNeSuisPasInscrit(): ?bool
    {
        return $this->sortiesDontJeNeSuisPasInscrit;
    }

    public function setSortiesDontJeNeSuisPasInscrit(bool $sortiesDontJeNeSuisPasInscrit): static
    {
        $this->sortiesDontJeNeSuisPasInscrit = $sortiesDontJeNeSuisPasInscrit;

        return $this;
    }

    public function isSortiesPassees(): ?bool
    {
        return $this->sortiesPassees;
    }

    public function setSortiesPassees(bool $sortiesPassees): static
    {
        $this->sortiesPassees = $sortiesPassees;

        return $this;
    }


}
