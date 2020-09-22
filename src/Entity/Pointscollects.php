<?php

namespace App\Entity;

use App\Repository\PointscollectsRepository;
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
 * @ORM\Entity(repositoryClass=PointscollectsRepository::class)
 * @ApiResource()
 */
class Pointscollects
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
    private $codePostal;

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
    private $coordLatLng;

    /**
     * @ORM\ManyToMany(targetEntity=Particuliers::class, mappedBy="pointsCollects")
     */
    private $particuliers;

    /**
     * @ORM\OneToMany(targetEntity=Materiels::class, mappedBy="pointCollect")
     */
    private $materiels;

    public function __construct()
    {
        $this->particuliers = new ArrayCollection();
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

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(?string $codePostal): self
    {
        $this->codePostal = $codePostal;

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
        return $this->coordLatLng;
    }

    public function setCoordLatLng(?string $coordLatLng): self
    {
        $this->coordLatLng = $coordLatLng;

        return $this;
    }

    /**
     * @return Collection|Particuliers[]
     */
    public function getParticuliers(): Collection
    {
        return $this->particuliers;
    }

    public function addParticulier(Particuliers $particulier): self
    {
        if (!$this->particuliers->contains($particulier)) {
            $this->particuliers[] = $particulier;
            $particulier->addPointsCollect($this);
        }

        return $this;
    }

    public function removeParticulier(Particuliers $particulier): self
    {
        if ($this->particuliers->contains($particulier)) {
            $this->particuliers->removeElement($particulier);
            $particulier->removePointsCollect($this);
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
            $materiel->setPointCollect($this);
        }

        return $this;
    }

    public function removeMateriel(Materiels $materiel): self
    {
        if ($this->materiels->contains($materiel)) {
            $this->materiels->removeElement($materiel);
            // set the owning side to null (unless already changed)
            if ($materiel->getPointCollect() === $this) {
                $materiel->setPointCollect(null);
            }
        }

        return $this;
    }
}
