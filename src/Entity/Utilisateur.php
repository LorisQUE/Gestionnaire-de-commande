<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @UniqueEntity(fields={"Email"}, message="There is already an account with this Email")
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $Email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Pseudonyme;

    /**
     * @ORM\OneToMany(targetEntity=Gamme::class, mappedBy="Superviseur")
     */
    private $Gammes;

    /**
     * @ORM\OneToMany(targetEntity=PosteDeTravail::class, mappedBy="Ouvrier")
     */
    private $PosteDeTravail;

    /**
     * @ORM\OneToMany(targetEntity=OperationRealisation::class, mappedBy="Operateur")
     */
    private $Realisations;

    /**
     * @ORM\OneToMany(targetEntity=GammeRealisation::class, mappedBy="Superviseur")
     */
    private $GammeRealisations;

    public function __construct()
    {
        $this->Gammes = new ArrayCollection();
        $this->PosteDeTravail = new ArrayCollection();
        $this->Realisations = new ArrayCollection();
        $this->GammeRealisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->Email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudonyme(): ?string
    {
        return $this->Pseudonyme;
    }

    public function setPseudonyme(string $Pseudonyme): self
    {
        $this->Pseudonyme = $Pseudonyme;

        return $this;
    }

    /**
     * @return Collection|Gamme[]
     */
    public function getGammes(): Collection
    {
        return $this->Gammes;
    }

    public function addGamme(Gamme $gamme): self
    {
        if (!$this->Gammes->contains($gamme)) {
            $this->Gammes[] = $gamme;
            $gamme->setSuperviseur($this);
        }

        return $this;
    }

    public function removeGamme(Gamme $gamme): self
    {
        if ($this->Gammes->removeElement($gamme)) {
            // set the owning side to null (unless already changed)
            if ($gamme->getSuperviseur() === $this) {
                $gamme->setSuperviseur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PosteDeTravail[]
     */
    public function getPosteDeTravail(): Collection
    {
        return $this->PosteDeTravail;
    }

    public function addPosteDeTravail(PosteDeTravail $posteDeTravail): self
    {
        if (!$this->PosteDeTravail->contains($posteDeTravail)) {
            $this->PosteDeTravail[] = $posteDeTravail;
            $posteDeTravail->setOuvrier($this);
        }

        return $this;
    }

    public function removePosteDeTravail(PosteDeTravail $posteDeTravail): self
    {
        if ($this->PosteDeTravail->removeElement($posteDeTravail)) {
            // set the owning side to null (unless already changed)
            if ($posteDeTravail->getOuvrier() === $this) {
                $posteDeTravail->setOuvrier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OperationRealisation[]
     */
    public function getRealisations(): Collection
    {
        return $this->Realisations;
    }

    public function addOperationRealisation(OperationRealisation $realisation): self
    {
        if (!$this->Realisations->contains($realisation)) {
            $this->Realisations[] = $realisation;
            $realisation->setOperateur($this);
        }

        return $this;
    }

    public function removeOperationRealisation(OperationRealisation $realisation): self
    {
        if ($this->Realisations->removeElement($realisation)) {
            // set the owning side to null (unless already changed)
            if ($realisation->getOperateur() === $this) {
                $realisation->setOperateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GammeRealisation[]
     */
    public function getGammeRealisations(): Collection
    {
        return $this->GammeRealisations;
    }

    public function addGammeRealisation(GammeRealisation $GammeRealisation): self
    {
        if (!$this->GammeRealisations->contains($GammeRealisation)) {
            $this->GammeRealisations[] = $GammeRealisation;
            $GammeRealisation->setSuperviseur($this);
        }

        return $this;
    }

    public function removeGammeRealisation(GammeRealisation $GammeRealisation): self
    {
        if ($this->GammeRealisations->removeElement($GammeRealisation)) {
            // set the owning side to null (unless already changed)
            if ($GammeRealisation->getSuperviseur() === $this) {
                $GammeRealisation->setSuperviseur(null);
            }
        }

        return $this;
    }
}
