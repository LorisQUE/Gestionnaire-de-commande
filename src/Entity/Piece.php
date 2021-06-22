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
     * @ORM\OneToOne(targetEntity=Gamme::class, mappedBy="Piece", cascade={"persist", "remove"})
     */
    private $Gamme;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Reference;

    /**
     * @ORM\OneToMany(targetEntity=PieceRelation::class, mappedBy="PieceNecessaire", orphanRemoval=true)
     */
    private $PiecesNecessaires;

    /**
     * @ORM\OneToMany(targetEntity=PieceRelation::class, mappedBy="PieceProduite", orphanRemoval=true)
     */
    private $PiecesProduites;

    public function __construct()
    {
        $this->PiecesNecessaires = new ArrayCollection();
        $this->PiecesProduites = new ArrayCollection();
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

    /**
     * @return Collection|PieceRelation[]
     */
    public function getPiecesProduites(): Collection
    {
        //dump($this->PiecesNecessaires);
        return $this->PiecesNecessaires;
    }

    public function addPiecesProduite(PieceRelation $piecesNecessaire): self
    {
        if (!$this->PiecesNecessaires->contains($piecesNecessaire)) {
            $this->PiecesNecessaires[] = $piecesNecessaire;
            $piecesNecessaire->setPieceNecessaire($this);
        }

        return $this;
    }

    public function removePiecesProduite(PieceRelation $piecesNecessaire): self
    {
        if ($this->PiecesNecessaires->removeElement($piecesNecessaire)) {
            // set the owning side to null (unless already changed)
            if ($piecesNecessaire->getPieceNecessaire() === $this) {
                $piecesNecessaire->setPieceNecessaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PieceRelation[]
     */
    public function getPiecesNecessaires(): Collection
    {
        //dump($this->PiecesProduites);
        return $this->PiecesProduites;
    }

    public function addPiecesNecessaire(PieceRelation $piecesProduite): self
    {
        if (!$this->PiecesProduites->contains($piecesProduite)) {
            $this->PiecesProduites[] = $piecesProduite;
            $piecesProduite->setPieceProduite($this);
        }

        return $this;
    }

    public function removePiecesNecessaire(PieceRelation $piecesProduite): self
    {
        if ($this->PiecesProduites->removeElement($piecesProduite)) {
            // set the owning side to null (unless already changed)
            if ($piecesProduite->getPieceProduite() === $this) {
                $piecesProduite->setPieceProduite(null);
            }
        }

        return $this;
    }
}
