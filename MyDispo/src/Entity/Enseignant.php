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
    private $saisieFaite = false;

    /**
     * @ORM\OneToMany(targetEntity=LogEnseignant::class, mappedBy="enseignant")
     */
    private $logsEnseignant;

    /**
     * @ORM\ManyToMany(targetEntity=Formation::class, mappedBy="enseignants")
     */
    private $formations;

    /**
     * @ORM\OneToMany(targetEntity=Remarque::class, mappedBy="enseignant")
     */
    private $remarques;

    /**
     * @ORM\OneToMany(targetEntity=ModeleMail::class, mappedBy="enseignant")
     */
    private $modelesMails;

    /**
     * @ORM\OneToMany(targetEntity=Creneau::class, mappedBy="enseignant")
     */
    private $creneaux;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateSaisie;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDerniereModif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $premierMailRecu= false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mailRelanceRecu= false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datePremierMail;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDerniereRelance;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbRelance= 0;

    public function __construct()
    {
        $this->logsEnseignant = new ArrayCollection();
        $this->formations = new ArrayCollection();
        $this->remarques = new ArrayCollection();
        $this->modelesMails = new ArrayCollection();
        $this->creneaux = new ArrayCollection();
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

    /**
     * @return Collection|Formation[]
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
            $formation->addEnseignant($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->contains($formation)) {
            $this->formations->removeElement($formation);
            $formation->removeEnseignant($this);
        }

        return $this;
    }

    /**
     * @return Collection|Remarque[]
     */
    public function getRemarques(): Collection
    {
        return $this->remarques;
    }

    public function addRemarque(Remarque $remarque): self
    {
        if (!$this->remarques->contains($remarque)) {
            $this->remarques[] = $remarque;
            $remarque->setEnseignant($this);
        }

        return $this;
    }

    public function removeRemarque(Remarque $remarque): self
    {
        if ($this->remarques->contains($remarque)) {
            $this->remarques->removeElement($remarque);
            // set the owning side to null (unless already changed)
            if ($remarque->getEnseignant() === $this) {
                $remarque->setEnseignant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ModeleMail[]
     */
    public function getModelesMails(): Collection
    {
        return $this->modelesMails;
    }

    public function addModelesMail(ModeleMail $modelesMail): self
    {
        if (!$this->modelesMails->contains($modelesMail)) {
            $this->modelesMails[] = $modelesMail;
            $modelesMail->setEnseignant($this);
        }

        return $this;
    }

    public function removeModelesMail(ModeleMail $modelesMail): self
    {
        if ($this->modelesMails->contains($modelesMail)) {
            $this->modelesMails->removeElement($modelesMail);
            // set the owning side to null (unless already changed)
            if ($modelesMail->getEnseignant() === $this) {
                $modelesMail->setEnseignant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Creneau[]
     */
    public function getCreneaux(): Collection
    {
        return $this->creneaux;
    }

    public function addCreneaux(Creneau $creneaux): self
    {
        if (!$this->creneaux->contains($creneaux)) {
            $this->creneaux[] = $creneaux;
            $creneaux->setEnseignant($this);
        }

        return $this;
    }

    public function removeCreneaux(Creneau $creneaux): self
    {
        if ($this->creneaux->contains($creneaux)) {
            $this->creneaux->removeElement($creneaux);
            // set the owning side to null (unless already changed)
            if ($creneaux->getEnseignant() === $this) {
                $creneaux->setEnseignant(null);
            }
        }

        return $this;
    }

    public function getDateSaisie(): ?\DateTime
    {
        return $this->dateSaisie;
    }

    public function setDateSaisie(?\DateTime $dateSaisie): self
    {
        $this->dateSaisie = $dateSaisie;

        return $this;
    }

    public function getDateDerniereModif(): ?\DateTime
    {
        return $this->dateDerniereModif;
    }

    public function setDateDerniereModif(?\DateTime $dateDerniereModif): self
    {
        $this->dateDerniereModif = $dateDerniereModif;

        return $this;
    }

    public function getPremierMailRecu(): ?bool
    {
        return $this->premierMailRecu;
    }

    public function setPremierMailRecu(bool $premierMailRecu): self
    {
        $this->premierMailRecu = $premierMailRecu;

        return $this;
    }

    public function getMailRelanceRecu(): ?bool
    {
        return $this->mailRelanceRecu;
    }

    public function setMailRelanceRecu(bool $mailRelanceRecu): self
    {
        $this->mailRelanceRecu = $mailRelanceRecu;

        return $this;
    }

    public function getDatePremierMail(): ?\DateTime
    {
        return $this->datePremierMail;
    }

    public function setDatePremierMail(?\DateTime $datePremierMail): self
    {
        $this->datePremierMail = $datePremierMail;

        return $this;
    }

    public function getDateDerniereRelance(): ?\DateTime
    {
        return $this->dateDerniereRelance;
    }

    public function setDateDerniereRelance(?\DateTime $dateDerniereRelance): self
    {
        $this->dateDerniereRelance = $dateDerniereRelance;

        return $this;
    }

    public function getNbRelance(): ?int
    {
        return $this->nbRelance;
    }

    public function setNbRelance(int $nbRelance): self
    {
        $this->nbRelance = $nbRelance;

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom;
    }



}
