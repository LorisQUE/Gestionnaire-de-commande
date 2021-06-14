<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OperationRepository::class)
 */
class Operation
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
     * @ORM\Column(type="integer")
     */
    private $Duree;

    /**
     * @ORM\OneToMany(targetEntity=OperationRealisation::class, mappedBy="Operation")
     */
    private $Realisations;

    /**
     * @ORM\ManyToOne(targetEntity=Machine::class, inversedBy="Operations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Machine;

    /**
     * @ORM\ManyToOne(targetEntity=PosteDeTravail::class, inversedBy="Operations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $PosteDeTravail;

    /**
     * @ORM\ManyToOne(targetEntity=Gamme::class, inversedBy="Operations")
     */
    private $Gamme;

    public function __construct()
    {
        $this->Realisations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLibelle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->Libelle;
    }

    public function setLibelle(string $Libelle): self
    {
        $this->Libelle = $Libelle;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->Duree;
    }

    public function setDuree(int $Duree): self
    {
        $this->Duree = $Duree;

        return $this;
    }

    /**
     * @return Collection|OperationRealisation[]
     */
    public function getRealisations(): Collection
    {
        return $this->Realisations;
    }

    public function addRealisation(OperationRealisation $realisation): self
    {
        if (!$this->Realisations->contains($realisation)) {
            $this->Realisations[] = $realisation;
            $realisation->setOperation($this);
        }

        return $this;
    }

    public function removeRealisation(OperationRealisation $realisation): self
    {
        if ($this->Realisations->removeElement($realisation)) {
            // set the owning side to null (unless already changed)
            if ($realisation->getOperation() === $this) {
                $realisation->setOperation(null);
            }
        }

        return $this;
    }

    public function getMachine(): ?Machine
    {
        return $this->Machine;
    }

    public function setMachine(?Machine $Machine): self
    {
        $this->Machine = $Machine;

        return $this;
    }

    public function getPosteDeTravail(): ?PosteDeTravail
    {
        return $this->PosteDeTravail;
    }

    public function setPosteDeTravail(?PosteDeTravail $PosteDeTravail): self
    {
        $this->PosteDeTravail = $PosteDeTravail;

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
}
