<?php

namespace App\Entity;

use App\Repository\DistributeursRepository;
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
 * @ORM\Entity(repositoryClass=DistributeursRepository::class)
 * @ApiResource()
 */
class Distributeurs
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
    private $libelle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $codePostale;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $telFixe;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $telPortable;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $CoordLatLng;

    /**
     * @ORM\OneToMany(targetEntity=Materiels::class, mappedBy="distributeurs")
     */
    private $materiels;

    public function __construct()
    {
        $this->materiels = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLibelle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostale(): ?string
    {
        return $this->codePostale;
    }

    public function setCodePostale(?string $codePostale): self
    {
        $this->codePostale = $codePostale;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTelFixe(): ?string
    {
        return $this->telFixe;
    }

    public function setTelFixe(?string $telFixe): self
    {
        $this->telFixe = $telFixe;

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

    public function getCoordLatLng(): ?string
    {
        return $this->CoordLatLng;
    }

    public function setCoordLatLng(?string $CoordLatLng): self
    {
        $this->CoordLatLng = $CoordLatLng;

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
            $materiel->setDistributeurs($this);
        }

        return $this;
    }

    public function removeMateriel(Materiels $materiel): self
    {
        if ($this->materiels->contains($materiel)) {
            $this->materiels->removeElement($materiel);
            // set the owning side to null (unless already changed)
            if ($materiel->getDistributeurs() === $this) {
                $materiel->setDistributeurs(null);
            }
        }

        return $this;
    }
}
