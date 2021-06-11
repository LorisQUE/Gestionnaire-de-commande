<?php

namespace App\Entity;

use App\Repository\PieceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PieceRepository::class)
 */
class Piece
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
    private $Quantite;

    /**
     * @ORM\Column(type="float")
     */
    private $Prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Type;

    /**
     * @ORM\Column(type="float")
     */
    private $PrixCatalogue;

    /**
     * @ORM\ManyToOne(targetEntity=Fournisseur::class, inversedBy="PiecesFournies")
     */
    private $Fournisseur;

    /**
     * @ORM\ManyToMany(targetEntity=Piece::class, inversedBy="PiecesNecessaire")
     */
    private $PiecesParentes;

    /**
     * @ORM\ManyToMany(targetEntity=Piece::class, mappedBy="PiecesParentes")
     */
    private $PiecesNecessaire;

    /**
     * @ORM\OneToOne(targetEntity=Gamme::class, mappedBy="Piece", cascade={"persist", "remove"})
     */
    private $Gamme;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Reference;

    public function __construct()
    {
        $this->PiecesParentes = new ArrayCollection();
        $this->PiecesNecessaire = new ArrayCollection();
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

    public function getQuantite(): ?int
    {
        return $this->Quantite;
    }

    public function setQuantite(int $Quantite): self
    {
        $this->Quantite = $Quantite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getPrixCatalogue(): ?float
    {
        return $this->PrixCatalogue;
    }

    public function setPrixCatalogue(float $PrixCatalogue): self
    {
        $this->PrixCatalogue = $PrixCatalogue;

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->Fournisseur;
    }

    public function setFournisseur(?Fournisseur $Fournisseur): self
    {
        $this->Fournisseur = $Fournisseur;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getPiecesParentes(): Collection
    {
        return $this->PiecesParentes;
    }

    public function addPiecesParente(self $piecesParente): self
    {
        if (!$this->PiecesParentes->contains($piecesParente)) {
            $this->PiecesParentes[] = $piecesParente;
        }

        return $this;
    }

    public function removePiecesParente(self $piecesParente): self
    {
        $this->PiecesParentes->removeElement($piecesParente);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getPiecesNecessaire(): Collection
    {
        return $this->PiecesNecessaire;
    }

    public function addPiecesNecessaire(self $piecesNecessaire): self
    {
        if (!$this->PiecesNecessaire->contains($piecesNecessaire)) {
            $this->PiecesNecessaire[] = $piecesNecessaire;
            $piecesNecessaire->addPiecesParente($this);
        }

        return $this;
    }

    public function removePiecesNecessaire(self $piecesNecessaire): self
    {
        if ($this->PiecesNecessaire->removeElement($piecesNecessaire)) {
            $piecesNecessaire->removePiecesParente($this);
        }

        return $this;
    }

    public function getGamme(): ?Gamme
    {
        return $this->Gamme;
    }

    public function setGamme(Gamme $Gamme): self
    {
        // set the owning side of the relation if necessary
        if ($Gamme->getPiece() !== $this) {
            $Gamme->setPiece($this);
        }

        $this->Gamme = $Gamme;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->Reference;
    }

    public function setReference(string $Reference): self
    {
        $this->Reference = $Reference;

        return $this;
    }
}
