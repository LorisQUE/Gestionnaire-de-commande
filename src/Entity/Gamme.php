<?php

namespace App\Entity;

use App\Repository\GammeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GammeRepository::class)
 */
class Gamme
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
     * @ORM\OneToOne(targetEntity=Piece::class, inversedBy="Gamme")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Piece;

    /**
     * @ORM\ManyToMany(targetEntity=Operation::class, inversedBy="Gammes")
     */
    private $Operations;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="Gammes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Superviseur;

    /**
     * @ORM\OneToMany(targetEntity=GammeRealisation::class, mappedBy="Gamme")
     */
    private $GammeRealisations;

    public function __construct()
    {
        $this->Operations = new ArrayCollection();
        $this->GammeRealisations = new ArrayCollection();
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

    public function getPiece(): ?Piece
    {
        return $this->Piece;
    }

    public function setPiece(Piece $Piece): self
    {
        $this->Piece = $Piece;

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
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self
    {
        $this->Operations->removeElement($operation);

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
     * @return Collection|GammeRealisation[]
     */
    public function getGammeRealisations(): Collection
    {
        return $this->GammeRealisations;
    }

    public function addGammeRealisation(GammeRealisation $gammeRealisation): self
    {
        if (!$this->GammeRealisations->contains($gammeRealisation)) {
            $this->GammeRealisations[] = $gammeRealisation;
            $gammeRealisation->setGamme($this);
        }

        return $this;
    }

    public function removeGammeRealisation(GammeRealisation $gammeRealisation): self
    {
        if ($this->GammeRealisations->removeElement($gammeRealisation)) {
            // set the owning side to null (unless already changed)
            if ($gammeRealisation->getGamme() === $this) {
                $gammeRealisation->setGamme(null);
            }
        }

        return $this;
    }
}
