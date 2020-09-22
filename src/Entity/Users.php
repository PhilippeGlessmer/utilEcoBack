<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ApiResource()
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $passwordFirst;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $civil;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $Prenom;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $telPortable;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tauxHoraire;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToOne(targetEntity=Fournisseurs::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $fournisseurs;

    /**
     * @ORM\OneToMany(targetEntity=TestsProcess::class, mappedBy="User")
     */
    private $testsProcesses;

    public function __construct()
    {
        $this->testsProcesses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER

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
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPasswordFirst(): ?string
    {
        return $this->passwordFirst;
    }

    public function setPasswordFirst(?string $passwordFirst): self
    {
        $this->passwordFirst = $passwordFirst;

        return $this;
    }

    public function getCivil(): ?string
    {
        return $this->civil;
    }

    public function setCivil(string $civil): self
    {
        $this->civil = $civil;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getTelPortable(): ?string
    {
        return $this->telPortable;
    }

    public function setTelPortable(?string $telPortable): self
    {
        $this->telPortable = $telPortable;

        return $this;
    }

    public function getTauxHoraire(): ?float
    {
        return $this->tauxHoraire;
    }

    public function setTauxHoraire(?float $tauxHoraire): self
    {
        $this->tauxHoraire = $tauxHoraire;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getFournisseurs(): ?Fournisseurs
    {
        return $this->fournisseurs;
    }

    public function setFournisseurs(Fournisseurs $fournisseurs): self
    {
        $this->fournisseurs = $fournisseurs;

        // set the owning side of the relation if necessary
        if ($fournisseurs->getUser() !== $this) {
            $fournisseurs->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|TestsProcess[]
     */
    public function getTestsProcesses(): Collection
    {
        return $this->testsProcesses;
    }

    public function addTestsProcess(TestsProcess $testsProcess): self
    {
        if (!$this->testsProcesses->contains($testsProcess)) {
            $this->testsProcesses[] = $testsProcess;
            $testsProcess->setUser($this);
        }

        return $this;
    }

    public function removeTestsProcess(TestsProcess $testsProcess): self
    {
        if ($this->testsProcesses->contains($testsProcess)) {
            $this->testsProcesses->removeElement($testsProcess);
            // set the owning side to null (unless already changed)
            if ($testsProcess->getUser() === $this) {
                $testsProcess->setUser(null);
            }
        }

        return $this;
    }
}
