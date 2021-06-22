<?php

namespace App\Entity;

use App\Repository\GammeRealisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GammeRealisationRepository::class)
 */
class GammeRealisation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Libelle;

    /**
     * @ORM\ManyToOne(targetEntity=Gamme::class, inversedBy="GammeRealisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Gamme;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="GammeRealisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Superviseur;

    /**
     * @ORM\OneToMany(targetEntity=OperationRealisation::class, mappedBy="GammeRealisation", cascade={"remove"})
     */
    private $OperationRealisations;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $Date;

    public function __construct()
    {
        $this->OperationRealisations = new ArrayCollection();
        $this->Date = new \DateTime();
        $this->Date->setTimezone( new \DateTimeZone("Europe/Paris"));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->Libelle;
    }

    public function setLibelle(string $Libelle = null): self
    {
        $this->Libelle = $Libelle;

        return $this;
    }

    public function getGamme(): ?Gamme
    {
        return $this->Gamme;
    }

    public function setGamme(?Gamme $Gamme): self
    {
        $this->Gamme = $Gamme;

        return $this;
    }

    public function getSuperviseur(): ?Utilisateur
    {
        return $this->Superviseur;
    }

    public function setSuperviseur(?Utilisateur $Superviseur): self
    {
        $this->Superviseur = $Superviseur;

        return $this;
    }

    /**
     * @return Collection|OperationRealisation[]
     */
    public function getOperationRealisations(): Collection
    {
        return $this->OperationRealisations;
    }

    public function addOperationRealisation(OperationRealisation $operationRealisation): self
    {
        if (!$this->OperationRealisations->contains($operationRealisation)) {
            $this->OperationRealisations[] = $operationRealisation;
            $operationRealisation->setGammeRealisation($this);
        }

        return $this;
    }

    public function removeOperationRealisation(OperationRealisation $operationRealisation): self
    {
        if ($this->OperationRealisations->removeElement($operationRealisation)) {
            // set the owning side to null (unless already changed)
            if ($operationRealisation->getGammeRealisation() === $this) {
                $operationRealisation->setGammeRealisation(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date = null): self
    {
        $this->date = $Date;

        return $this;
    }
}
