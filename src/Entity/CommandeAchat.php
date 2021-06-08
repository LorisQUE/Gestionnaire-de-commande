<?php

namespace App\Entity;

use App\Repository\CommandeAchatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeAchatRepository::class)
 */
class CommandeAchat
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
     * @ORM\Column(type="datetime")
     */
    private $DatePrevu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DatePrevue;

    /**
     * @ORM\Column(type="float")
     */
    private $Prix;

    /**
     * @ORM\ManyToMany(targetEntity=Piece::class)
     */
    private $Pieces;

    public function __construct()
    {
        $this->Pieces = new ArrayCollection();
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

    public function getDatePrevu(): ?\DateTimeInterface
    {
        return $this->DatePrevu;
    }

    public function setDatePrevu(\DateTimeInterface $DatePrevu): self
    {
        $this->DatePrevu = $DatePrevu;

        return $this;
    }

    public function getDatePrevue(): ?\DateTimeInterface
    {
        return $this->DatePrevue;
    }

    public function setDatePrevue(\DateTimeInterface $DatePrevue): self
    {
        $this->DatePrevue = $DatePrevue;

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

    /**
     * @return Collection|Piece[]
     */
    public function getPieces(): Collection
    {
        return $this->Pieces;
    }

    public function addPiece(Piece $piece): self
    {
        if (!$this->Pieces->contains($piece)) {
            $this->Pieces[] = $piece;
        }

        return $this;
    }

    public function removePiece(Piece $piece): self
    {
        $this->Pieces->removeElement($piece);

        return $this;
    }
}
