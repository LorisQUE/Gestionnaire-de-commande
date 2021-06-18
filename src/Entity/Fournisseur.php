<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FournisseurRepository::class)
 */
class Fournisseur
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
     * @ORM\Column(type="string", length=255)
     */
    private $Adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Ville;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Pays;

    /**
     * @ORM\OneToMany(targetEntity=Piece::class, mappedBy="Fournisseur")
     */
    private $PiecesFournies;

    public function __construct()
    {
        $this->PiecesFournies = new ArrayCollection();
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

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->Ville;
    }

    public function setVille(string $Ville): self
    {
        $this->Ville = $Ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->Pays;
    }

    public function setPays(string $Pays): self
    {
        $this->Pays = $Pays;

        return $this;
    }

    /**
     * @return Collection|Piece[]
     */
    public function getPiecesFournies(): Collection
    {
        return $this->PiecesFournies;
    }

    public function addPiecesFournie(Piece $piecesFourny): self
    {
        if (!$this->PiecesFournies->contains($piecesFourny)) {
            $this->PiecesFournies[] = $piecesFourny;
            $piecesFourny->setFournisseur($this);
        }

        return $this;
    }

    public function removePiecesFourny(Piece $piecesFourny): self
    {
        if ($this->PiecesFournies->removeElement($piecesFourny)) {
            // set the owning side to null (unless already changed)
            if ($piecesFourny->getFournisseur() === $this) {
                $piecesFourny->setFournisseur(null);
            }
        }

        return $this;
    }
}
