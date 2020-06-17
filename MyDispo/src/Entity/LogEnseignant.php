<?php

namespace App\Entity;

use App\Repository\LogEnseignantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogEnseignantRepository::class)
 */
class LogEnseignant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateLog;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeAction;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zone;

    /**
     * @ORM\Column(type="text", length=2000)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Enseignant::class, inversedBy="logsEnseignant")
     */
    private $enseignant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateLog(): ?\DateTimeInterface
    {
        return $this->dateLog;
    }

    public function setDateLog(\DateTimeInterface $dateLog): self
    {
        $this->dateLog = $dateLog;

        return $this;
    }

    public function getTypeAction(): ?string
    {
        return $this->typeAction;
    }

    public function setTypeAction(string $typeAction): self
    {
        $this->typeAction = $typeAction;

        return $this;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(?string $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEnseignant(): ?Enseignant
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Enseignant $enseignant): self
    {
        $this->enseignant = $enseignant;

        return $this;
    }
}
