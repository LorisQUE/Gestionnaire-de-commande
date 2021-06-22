<?php

namespace App\Entity;

use App\Repository\PosteDeTravailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PosteDeTravailRepository::class)
 */
class PosteDeTravail
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
     * @ORM\OneToMany(targetEntity=OperationRealisation::class, mappedBy="PosteDeTravail")
     */
    private $Realisations;

    /**
     * @ORM\OneToMany(targetEntity=Machine::class, mappedBy="PosteDeTravail")
     */
    private $Machines;

    /**
     * @ORM\OneToMany(targetEntity=Operation::class, mappedBy="PosteDeTravail")
     */
    private $Operations;

    /**
     * @ORM\ManyToMany(targetEntity=Utilisateur::class, inversedBy="PostesDeTravail")
     */
    private $Ouvriers;

    public function __construct()
    {
        $this->Realisations = new ArrayCollection();
        $this->Machines = new ArrayCollection();
        $this->Operations = new ArrayCollection();
        $this->Ouvriers = new ArrayCollection();
    }

    public function  __toString(){
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
            $realisation->setPosteDeTravail($this);
        }

        return $this;
    }

    public function removeRealisation(OperationRealisation $realisation): self
    {
        if ($this->Realisations->removeElement($realisation)) {
            // set the owning side to null (unless already changed)
            if ($realisation->getPosteDeTravail() === $this) {
                $realisation->setPosteDeTravail(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Machine[]
     */
    public function getMachines(): Collection
    {
        return $this->Machines;
    }

    public function addMachine(Machine $machine): self
    {
        if (!$this->Machines->contains($machine)) {
            $this->Machines[] = $machine;
            $machine->setPosteDeTravail($this);
        }

        return $this;
    }

    public function removeMachine(Machine $machine): self
    {
        if ($this->Machines->removeElement($machine)) {
            // set the owning side to null (unless already changed)
            if ($machine->getPosteDeTravail() === $this) {
                $machine->setPosteDeTravail(null);
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
            $operation->setPosteDeTravail($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self
    {
        if ($this->Operations->removeElement($operation)) {
            // set the owning side to null (unless already changed)
            if ($operation->getPosteDeTravail() === $this) {
                $operation->setPosteDeTravail(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getOuvriers(): Collection
    {
        return $this->Ouvriers;
    }

    public function addOuvrier(Utilisateur $ouvrier): self
    {
        if (!$this->Ouvriers->contains($ouvrier)) {
            $this->Ouvriers[] = $ouvrier;
        }

        return $this;
    }

    public function removeOuvrier(Utilisateur $ouvrier): self
    {
        $this->Ouvriers->removeElement($ouvrier);

        return $this;
    }
}
