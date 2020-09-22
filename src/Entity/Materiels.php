<?php

namespace App\Entity;

use App\Repository\MaterielsRepository;
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
 * @ORM\Entity(repositoryClass=MaterielsRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"role_commercial"}},
 *    collectionOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/materiels",
 *              "normalization_context"= {
 *                  "groups"={"role_commercial"}
 *              }
 *          },
 *          "post"={
 *              "method"="POST",
 *              "path"="/materiels",
 *              "denormalization_context"= {
 *                  "groups"={"role_commercial"}
 *              }
 *          }
 *      },
 *    itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "path"="/materiels/{id}",
 *              "normalization_context"= {
 *                  "groups"={"role_commercial"}
 *              }
 *          },
 *          "put"={
 *              "method"="PUT",
 *              "path"="/materiels/{id}",
 *              "denormalization_context"= {
 *                  "groups"={"role_commercial"}
 *              },
 *              "normalization_context"= {
 *                  "groups"={"role_commercial"}
 *              }
 *          },
 *          "delete"={
 *              "method"="DELETE",
 *              "path"="/materiels/{id}"
 *          }
 *      }
 * )
 */
class Materiels
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"role_commercial"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Models::class, inversedBy="materiels")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"role_commercial"})
     */
    private $modele;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"role_commercial"})
     */
    private $nSerieFabricant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"role_commercial"})
     */
    private $nSerieFournisseur;

    /**
     * @ORM\ManyToOne(targetEntity=Lots::class, inversedBy="materiels")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"role_commercial"})
     */
    private $lots;

    /**
     * @ORM\ManyToOne(targetEntity=Particuliers::class, inversedBy="materiels")
     * @Groups({"role_commercial"})
     */
    private $particuliers;

    /**
     * @ORM\ManyToOne(targetEntity=Distributeurs::class, inversedBy="materiels")
     * @Groups({"role_commercial"})
     */
    private $distributeurs;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Groups({"role_commercial"})
     */
    private $contactUsername;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Groups({"role_commercial"})
     */
    private $contactEmail;

    /**
     * @ORM\ManyToOne(targetEntity=Pointscollects::class, inversedBy="materiels")
     * @Groups({"role_commercial"})
     */
    private $pointCollect;

    /**
     * @ORM\OneToMany(targetEntity=TestsProcess::class, mappedBy="materiel")
     * @Groups({"role_commercial"})
     */
    private $testsProcesses;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Groups({"role_commercial"})
     */
    private $etat;

    public function __construct()
    {
        $this->testsProcesses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModele(): ?Models
    {
        return $this->modele;
    }

    public function setModele(?Models $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getNSerieFabricant(): ?string
    {
        return $this->nSerieFabricant;
    }

    public function setNSerieFabricant(?string $nSerieFabricant): self
    {
        $this->nSerieFabricant = $nSerieFabricant;

        return $this;
    }

    public function getNSerieFournisseur(): ?string
    {
        return $this->nSerieFournisseur;
    }

    public function setNSerieFournisseur(?string $nSerieFournisseur): self
    {
        $this->nSerieFournisseur = $nSerieFournisseur;

        return $this;
    }

    public function getLots(): ?Lots
    {
        return $this->lots;
    }

    public function setLots(?Lots $lots): self
    {
        $this->lots = $lots;

        return $this;
    }

    public function getParticuliers(): ?Particuliers
    {
        return $this->particuliers;
    }

    public function setParticuliers(?Particuliers $particuliers): self
    {
        $this->particuliers = $particuliers;

        return $this;
    }

    public function getDistributeurs(): ?Distributeurs
    {
        return $this->distributeurs;
    }

    public function setDistributeurs(?Distributeurs $distributeurs): self
    {
        $this->distributeurs = $distributeurs;

        return $this;
    }

    public function getContactUsername(): ?string
    {
        return $this->contactUsername;
    }

    public function setContactUsername(?string $contactUsername): self
    {
        $this->contactUsername = $contactUsername;

        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): self
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    public function getPointCollect(): ?Pointscollects
    {
        return $this->pointCollect;
    }

    public function setPointCollect(?Pointscollects $pointCollect): self
    {
        $this->pointCollect = $pointCollect;

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
            $testsProcess->setMateriel($this);
        }

        return $this;
    }

    public function removeTestsProcess(TestsProcess $testsProcess): self
    {
        if ($this->testsProcesses->contains($testsProcess)) {
            $this->testsProcesses->removeElement($testsProcess);
            // set the owning side to null (unless already changed)
            if ($testsProcess->getMateriel() === $this) {
                $testsProcess->setMateriel(null);
            }
        }

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
