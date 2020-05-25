<?php

namespace App\Entity;

use App\Repository\EnseignantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EnseignantRepository::class)
 */
class Enseignant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enSommeil;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="boolean")
     */
    private $saisieFaite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motDePasse;

    /**
     * @ORM\OneToMany(targetEntity=LogEnseignant::class, mappedBy="enseignant")
     */
    private $logsEnseignant;

    public function __construct()
    {
        $this->logsEnseignant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getEnSommeil(): ?bool
    {
        return $this->enSommeil;
    }

    public function setEnSommeil(bool $enSommeil): self
    {
        $this->enSommeil = $enSommeil;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getSaisieFaite(): ?bool
    {
        return $this->saisieFaite;
    }

    public function setSaisieFaite(bool $saisieFaite): self
    {
        $this->saisieFaite = $saisieFaite;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(?string $motDePasse): self
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    /**
     * @return Collection|LogEnseignant[]
     */
    public function getLogsEnseignant(): Collection
    {
        return $this->logsEnseignant;
    }

    public function addLogsEnseignant(LogEnseignant $logsEnseignant): self
    {
        if (!$this->logsEnseignant->contains($logsEnseignant)) {
            $this->logsEnseignant[] = $logsEnseignant;
            $logsEnseignant->setEnseignant($this);
        }

        return $this;
    }

    public function removeLogsEnseignant(LogEnseignant $logsEnseignant): self
    {
        if ($this->logsEnseignant->contains($logsEnseignant)) {
            $this->logsEnseignant->removeElement($logsEnseignant);
            // set the owning side to null (unless already changed)
            if ($logsEnseignant->getEnseignant() === $this) {
                $logsEnseignant->setEnseignant(null);
            }
        }

        return $this;
    }
}
