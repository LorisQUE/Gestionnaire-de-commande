<?php

namespace App\Entity;

use App\Repository\MachineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MachineRepository::class)
 */
class Machine
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
     * @ORM\OneToMany(targetEntity=OperationRealisation::class, mappedBy="Machine")
     */
    private $Realisations;

    /**
     * @ORM\OneToMany(targetEntity=Operation::class, mappedBy="Machine")
     */
    private $Operations;

    /**
     * @ORM\ManyToOne(targetEntity=PosteDeTravail::class, inversedBy="Machines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $PosteDeTravail;

    public function __construct()
    {
        $this->Realisations = new ArrayCollection();
        $this->Operations = new ArrayCollection();
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
            $realisation->setMachine($this);
        }

        return $this;
    }

    public function removeRealisation(OperationRealisation $realisation): self
    {
        if ($this->Realisations->removeElement($realisation)) {
            // set the owning side to null (unless already changed)
            if ($realisation->getMachine() === $this) {
                $realisation->setMachine(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Operation[]
     */
    public function getOperations(): Collection
    {
        return $this->Operations;
    }

    public function addOperation(Operation $operation): self
    {
        if (!$this->Operations->contains($operation)) {
            $this->Operations[] = $operation;
            $operation->setMachine($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self
    {
        if ($this->Operations->removeElement($operation)) {
            // set the owning side to null (unless already changed)
            if ($operation->getMachine() === $this) {
                $operation->setMachine(null);
            }
        }

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
}
