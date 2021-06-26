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
     * @ORM\OneToMany(targetEntity=LigneCommandeAchat::class, mappedBy="CommandeAchat", orphanRemoval=true, cascade={"persist"})
     */
    private $Lignes;

    /**
     * @ORM\ManyToOne(targetEntity=Fournisseur::class, inversedBy="Commandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Fournisseur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DatePrevue;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DateEffective;

    public function __construct()
    {
        $this->Lignes = new ArrayCollection();
        $this->DatePrevue = new \DateTime();
        $this->DatePrevue->setTimezone( new \DateTimeZone("Europe/Paris"));
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

    /**
     * @return Collection|LigneCommandeAchat[]
     */
    public function getLignes(): Collection
    {
        return $this->Lignes;
    }

    public function addLigne(LigneCommandeAchat $ligne): self
    {
        if (!$this->Lignes->contains($ligne)) {
            $this->Lignes[] = $ligne;
            $ligne->setCommandeAchat($this);
        }

        return $this;
    }

    public function removeLigne(LigneCommandeAchat $ligne): self
    {
        if ($this->Lignes->removeElement($ligne)) {
            // set the owning side to null (unless already changed)
            if ($ligne->getCommandeAchat() === $this) {
                $ligne->setCommandeAchat(null);
            }
        }

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

    public function getDatePrevue(): ?\DateTimeInterface
    {
        return $this->DatePrevue;
    }

    public function setDatePrevue(\DateTimeInterface $DatePrevue): self
    {
        $this->DatePrevue = $DatePrevue;

        return $this;
    }

    public function getDateEffective(): ?\DateTimeInterface
    {
        return $this->DateEffective;
    }

    public function setDateEffective(?\DateTimeInterface $DateEffective): self
    {
        $this->DateEffective = $DateEffective;

        return $this;
    }
}
