<?php

namespace App\Entity;

use App\Repository\FormulaireVacataireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormulaireVacataireRepository::class)
 */
class FormulaireVacataire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $echelleCalendrier;

    /**
     * @ORM\Column(type="string", length=3000)
     */
    private $texteHebdomadaire;

    /**
     * @ORM\Column(type="string", length=3000)
     */
    private $textePonctuel;

    /**
     * @ORM\Column(type="boolean")
     */
    private $remarquesHebdoActives;

    /**
     * @ORM\Column(type="boolean")
     */
    private $remarquesPonctuelActives;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estOuvert;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEchelleCalendrier(): ?int
    {
        return $this->echelleCalendrier;
    }

    public function setEchelleCalendrier(int $echelleCalendrier): self
    {
        $this->echelleCalendrier = $echelleCalendrier;

        return $this;
    }

    public function getTexteHebdomadaire(): ?string
    {
        return $this->texteHebdomadaire;
    }

    public function setTexteHebdomadaire(string $texteHebdomadaire): self
    {
        $this->texteHebdomadaire = $texteHebdomadaire;

        return $this;
    }

    public function getTextePonctuel(): ?string
    {
        return $this->textePonctuel;
    }

    public function setTextePonctuel(string $textePonctuel): self
    {
        $this->textePonctuel = $textePonctuel;

        return $this;
    }

    public function getRemarquesHebdoActives(): ?bool
    {
        return $this->remarquesHebdoActives;
    }

    public function setRemarquesHebdoActives(bool $remarquesHebdoActives): self
    {
        $this->remarquesHebdoActives = $remarquesHebdoActives;

        return $this;
    }

    public function getRemarquesPonctuelActives(): ?bool
    {
        return $this->remarquesPonctuelActives;
    }

    public function setRemarquesPonctuelActives(bool $remarquesPonctuelActives): self
    {
        $this->remarquesPonctuelActives = $remarquesPonctuelActives;

        return $this;
    }

    public function getEstOuvert(): ?bool
    {
        return $this->estOuvert;
    }

    public function setEstOuvert(bool $estOuvert): self
    {
        $this->estOuvert = $estOuvert;

        return $this;
    }
}
