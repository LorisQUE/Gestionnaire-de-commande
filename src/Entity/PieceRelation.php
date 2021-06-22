<?php

namespace App\Entity;

use App\Repository\PieceRelationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PieceRelationRepository::class)
 */
class PieceRelation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Quantite;

    /**
     * @ORM\ManyToOne(targetEntity=Piece::class, inversedBy="PiecesNecessaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $PieceNecessaire;

    /**
     * @ORM\ManyToOne(targetEntity=Piece::class, inversedBy="PiecesProduites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $PieceProduite;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPieceNecessaire(): ?Piece
    {
        return $this->PieceNecessaire;
    }

    public function setPieceNecessaire(?Piece $PieceNecessaire): self
    {
        $this->PieceNecessaire = $PieceNecessaire;

        return $this;
    }

    public function getPieceProduite(): ?Piece
    {
        return $this->PieceProduite;
    }

    public function setPieceProduite(?Piece $PieceProduite): self
    {
        $this->PieceProduite = $PieceProduite;

        return $this;
    }
}
