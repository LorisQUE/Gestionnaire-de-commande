<?php

namespace App\Entity;

use App\Repository\LigneDevisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneDevisRepository::class)
 */
class LigneDevis
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
     * @ORM\Column(type="float")
     */
    private $Prix;

    /**
     * @ORM\ManyToOne(targetEntity=Devis::class, inversedBy="Lignes")
     */
    private $Devis;

    /**
     * @ORM\ManyToOne(targetEntity=Piece::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Piece;

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

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getDevis(): ?Devis
    {
        return $this->Devis;
    }

    public function setDevis(?Devis $Devis): self
    {
        $this->Devis = $Devis;

        return $this;
    }

    public function getPiece(): ?Piece
    {
        return $this->Piece;
    }

    public function setPiece(?Piece $Piece): self
    {
        $this->Piece = $Piece;

        return $this;
    }
}
