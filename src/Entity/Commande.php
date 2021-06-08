<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
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
    private $Date;

    /**
     * @ORM\OneToMany(targetEntity=LigneCommande::class, mappedBy="Commande", orphanRemoval=true)
     */
    private $Lignes;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="Commandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Client;

    public function __construct()
    {
        $this->Lignes = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    /**
     * @return Collection|LigneCommande[]
     */
    public function getLignes(): Collection
    {
        return $this->Lignes;
    }

    public function addLigne(LigneCommande $ligne): self
    {
        if (!$this->Lignes->contains($ligne)) {
            $this->Lignes[] = $ligne;
            $ligne->setCommande($this);
        }

        return $this;
    }

    public function removeLigne(LigneCommande $ligne): self
    {
        if ($this->Lignes->removeElement($ligne)) {
            // set the owning side to null (unless already changed)
            if ($ligne->getCommande() === $this) {
                $ligne->setCommande(null);
            }
        }

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->Client;
    }

    public function setClient(?Client $Client): self
    {
        $this->Client = $Client;

        return $this;
    }
}
