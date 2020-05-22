<?php

namespace App\Entity;

use App\Repository\EnseignantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=EnseignantRepository::class)
 */
class Enseignant implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motDePasse;

    /**
     * @ORM\ManyToMany(targetEntity=Formation::class, inversedBy="enseignants")
     */
    private $enseigner;

    /**
     * @ORM\OneToMany(targetEntity=Log::class, mappedBy="enseignant")
     */
    private $generer;

    /**
     * @ORM\OneToMany(targetEntity=Remarque::class, mappedBy="enseignant")
     */
    private $ecrire;

    /**
     * @ORM\OneToMany(targetEntity=Creneau::class, mappedBy="enseignant")
     */
    private $saisir;

    /**
     * @ORM\OneToMany(targetEntity=ModeleMail::class, mappedBy="enseignant")
     */
    private $creer;

    /**
     * @ORM\OneToMany(targetEntity=Formulaire::class, mappedBy="enseignant")
     */
    private $definir;

    public function __construct()
    {
        $this->enseigner = new ArrayCollection();
        $this->generer = new ArrayCollection();
        $this->ecrire = new ArrayCollection();
        $this->saisir = new ArrayCollection();
        $this->creer = new ArrayCollection();
        $this->definir = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
     * @return Collection|Formation[]
     */
    public function getEnseigner(): Collection
    {
        return $this->enseigner;
    }

    public function addEnseigner(Formation $enseigner): self
    {
        if (!$this->enseigner->contains($enseigner)) {
            $this->enseigner[] = $enseigner;
        }

        return $this;
    }

    public function removeEnseigner(Formation $enseigner): self
    {
        if ($this->enseigner->contains($enseigner)) {
            $this->enseigner->removeElement($enseigner);
        }

        return $this;
    }

    /**
     * @return Collection|Log[]
     */
    public function getGenerer(): Collection
    {
        return $this->generer;
    }

    public function addGenerer(Log $generer): self
    {
        if (!$this->generer->contains($generer)) {
            $this->generer[] = $generer;
            $generer->setEnseignant($this);
        }

        return $this;
    }

    public function removeGenerer(Log $generer): self
    {
        if ($this->generer->contains($generer)) {
            $this->generer->removeElement($generer);
            // set the owning side to null (unless already changed)
            if ($generer->getEnseignant() === $this) {
                $generer->setEnseignant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Remarque[]
     */
    public function getEcrire(): Collection
    {
        return $this->ecrire;
    }

    public function addEcrire(Remarque $ecrire): self
    {
        if (!$this->ecrire->contains($ecrire)) {
            $this->ecrire[] = $ecrire;
            $ecrire->setEnseignant($this);
        }

        return $this;
    }

    public function removeEcrire(Remarque $ecrire): self
    {
        if ($this->ecrire->contains($ecrire)) {
            $this->ecrire->removeElement($ecrire);
            // set the owning side to null (unless already changed)
            if ($ecrire->getEnseignant() === $this) {
                $ecrire->setEnseignant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Creneau[]
     */
    public function getSaisir(): Collection
    {
        return $this->saisir;
    }

    public function addSaisir(Creneau $saisir): self
    {
        if (!$this->saisir->contains($saisir)) {
            $this->saisir[] = $saisir;
            $saisir->setEnseignant($this);
        }

        return $this;
    }

    public function removeSaisir(Creneau $saisir): self
    {
        if ($this->saisir->contains($saisir)) {
            $this->saisir->removeElement($saisir);
            // set the owning side to null (unless already changed)
            if ($saisir->getEnseignant() === $this) {
                $saisir->setEnseignant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ModeleMail[]
     */
    public function getCreer(): Collection
    {
        return $this->creer;
    }

    public function addCreer(ModeleMail $creer): self
    {
        if (!$this->creer->contains($creer)) {
            $this->creer[] = $creer;
            $creer->setEnseignant($this);
        }

        return $this;
    }

    public function removeCreer(ModeleMail $creer): self
    {
        if ($this->creer->contains($creer)) {
            $this->creer->removeElement($creer);
            // set the owning side to null (unless already changed)
            if ($creer->getEnseignant() === $this) {
                $creer->setEnseignant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Formulaire[]
     */
    public function getDefinir(): Collection
    {
        return $this->definir;
    }

    public function addDefinir(Formulaire $definir): self
    {
        if (!$this->definir->contains($definir)) {
            $this->definir[] = $definir;
            $definir->setEnseignant($this);
        }

        return $this;
    }

    public function removeDefinir(Formulaire $definir): self
    {
        if ($this->definir->contains($definir)) {
            $this->definir->removeElement($definir);
            // set the owning side to null (unless already changed)
            if ($definir->getEnseignant() === $this) {
                $definir->setEnseignant(null);
            }
        }

        return $this;
    }
}
