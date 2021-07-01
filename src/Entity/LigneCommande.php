<?php

namespace App\Entity;

use App\Repository\LigneCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneCommandeRepository::class)
 */
class LigneCommande
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
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="Lignes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Commande;

    /**
     * @ORM\ManyToOne(targetEntity=Piece::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Piece;

    public function __toString()
    {
        return $this->getQuantite()." * ".$this->getPiece()->getLibelle()." : ".$this->getQuantite()*$this->getPrix()."€";
    }

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

    public function getCommande(): ?Commande
    {
        return $this->Commande;
    }

    public function setCommande(?Commande $Commande): self
    {
        $this->Commande = $Commande;

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
