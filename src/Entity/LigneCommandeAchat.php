<?php

namespace App\Entity;

use App\Repository\LigneCommandeAchatRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneCommandeAchatRepository::class)
 */
class LigneCommandeAchat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $Prix;

    /**
     * @ORM\Column(type="integer")
     */
    private $Quantite;

    /**
     * @ORM\ManyToOne(targetEntity=Piece::class, inversedBy="LignesCommandeAchat")
     */
    private $Piece;

    /**
     * @ORM\ManyToOne(targetEntity=CommandeAchat::class, inversedBy="Lignes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $CommandeAchat;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuantite(): ?int
    {
        return $this->Quantite;
    }

    public function setQuantite(int $Quantite): self
    {
        $this->Quantite = $Quantite;

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

    public function getCommandeAchat(): ?CommandeAchat
    {
        return $this->CommandeAchat;
    }

    public function setCommandeAchat(?CommandeAchat $CommandeAchat): self
    {
        $this->CommandeAchat = $CommandeAchat;

        return $this;
    }
}
