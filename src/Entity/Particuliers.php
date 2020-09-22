<?php

namespace App\Entity;

use App\Repository\ParticuliersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
/**
 * @ORM\Entity(repositoryClass=ParticuliersRepository::class)
 * @ApiResource()
 */
class Particuliers
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity=Pointscollects::class, inversedBy="particuliers")
     */
    private $pointsCollects;

    /**
     * @ORM\OneToMany(targetEntity=Materiels::class, mappedBy="particuliers")
     */
    private $materiels;

    public function __construct()
    {
        $this->pointsCollects = new ArrayCollection();
        $this->materiels = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->getNom();
    }
    public function getId(): ?int
    {
        return $this->id;
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
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Pointscollects[]
     */
    public function getPointsCollects(): Collection
    {
        return $this->pointsCollects;
    }

    public function addPointsCollect(Pointscollects $pointsCollect): self
    {
        if (!$this->pointsCollects->contains($pointsCollect)) {
            $this->pointsCollects[] = $pointsCollect;
        }

        return $this;
    }

    public function removePointsCollect(Pointscollects $pointsCollect): self
    {
        if ($this->pointsCollects->contains($pointsCollect)) {
            $this->pointsCollects->removeElement($pointsCollect);
        }

        return $this;
    }

    /**
     * @return Collection|Materiels[]
     */
    public function getMateriels(): Collection
    {
        return $this->materiels;
    }

    public function addMateriel(Materiels $materiel): self
    {
        if (!$this->materiels->contains($materiel)) {
            $this->materiels[] = $materiel;
            $materiel->setParticuliers($this);
        }

        return $this;
    }

    public function removeMateriel(Materiels $materiel): self
    {
        if ($this->materiels->contains($materiel)) {
            $this->materiels->removeElement($materiel);
            // set the owning side to null (unless already changed)
            if ($materiel->getParticuliers() === $this) {
                $materiel->setParticuliers(null);
            }
        }

        return $this;
    }
}
