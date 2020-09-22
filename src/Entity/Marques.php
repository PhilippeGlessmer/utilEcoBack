<?php

namespace App\Entity;

use App\Repository\MarquesRepository;
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
 * @ORM\Entity(repositoryClass=MarquesRepository::class)
 * @ApiResource()
 */
class Marques
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
     * @ORM\OneToMany(targetEntity=Models::class, mappedBy="marque")
     */
    private $models;

    public function __construct()
    {
        $this->models = new ArrayCollection();
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

    /**
     * @return Collection|Models[]
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    public function addModel(Models $model): self
    {
        if (!$this->models->contains($model)) {
            $this->models[] = $model;
            $model->setMarque($this);
        }

        return $this;
    }

    public function removeModel(Models $model): self
    {
        if ($this->models->contains($model)) {
            $this->models->removeElement($model);
            // set the owning side to null (unless already changed)
            if ($model->getMarque() === $this) {
                $model->setMarque(null);
            }
        }

        return $this;
    }
}
