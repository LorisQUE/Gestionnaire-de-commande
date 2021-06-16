<?php

namespace App\Entity;

use App\Repository\OperationRealisationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OperationRealisationRepository::class)
 */
class OperationRealisation
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
    private $Duree;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="Realisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Operateur;

    /**
     * @ORM\ManyToOne(targetEntity=Operation::class, inversedBy="Realisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Operation;

    /**
     * @ORM\ManyToOne(targetEntity=PosteDeTravail::class, inversedBy="Realisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $PosteDeTravail;

    /**
     * @ORM\ManyToOne(targetEntity=Machine::class, inversedBy="Realisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Machine;

    /**
     * @ORM\ManyToOne(targetEntity=GammeRealisation::class, inversedBy="OperationRealisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $GammeRealisation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->Libelle;
    }

    public function setLibelle(string $Libelle = null): self
    {
        $this->Libelle = $Libelle;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->Duree;
    }

    public function setDuree(int $Duree = null): self
    {
        $this->Duree = $Duree;

        return $this;
    }

    public function getOperateur(): ?Utilisateur
    {
        return $this->Operateur;
    }

    public function setOperateur(?Utilisateur $Operateur): self
    {
        $this->Operateur = $Operateur;

        return $this;
    }

    public function getOperation(): ?Operation
    {
        return $this->Operation;
    }

    public function setOperation(?Operation $Operation): self
    {
        $this->Operation = $Operation;

        return $this;
    }

    public function getPosteDeTravail(): ?PosteDeTravail
    {
        return $this->PosteDeTravail;
    }

    public function setPosteDeTravail(?PosteDeTravail $PosteDeTravail): self
    {
        $this->PosteDeTravail = $PosteDeTravail;

        return $this;
    }

    public function getMachine(): ?Machine
    {
        return $this->Machine;
    }

    public function setMachine(?Machine $Machine): self
    {
        $this->Machine = $Machine;

        return $this;
    }

    public function getGammeRealisation(): ?GammeRealisation
    {
        return $this->GammeRealisation;
    }

    public function setGammeRealisation(?GammeRealisation $GammeRealisation): self
    {
        $this->GammeRealisation = $GammeRealisation;

        return $this;
    }
}
