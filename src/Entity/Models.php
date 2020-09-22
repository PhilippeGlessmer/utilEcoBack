<?php

namespace App\Entity;

use App\Repository\ModelsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;

/**
 * @ORM\Entity(repositoryClass=ModelsRepository::class)
 * @Vich\Uploadable
 * @ApiResource()
 */
class Models
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=Marques::class, inversedBy="models")
     * @ORM\JoinColumn(nullable=false)
     */
    private $marque;

    /**
     * @ORM\OneToMany(targetEntity=Materiels::class, mappedBy="modele")
     */
    private $materiels;

    /**
     * @ORM\ManyToOne(targetEntity=Types::class, inversedBy="models")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pilote;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $notice;

    /**
     * @Vich\UploadableField(mapping="modele_image", fileNameProperty="image")
     * @var File|null
     */
    private $imageFile;

    /**
     * @Vich\UploadableField(mapping="modele_driver", fileNameProperty="pilote")
     * @var File|null
     */
    private $piloteFile;

    /**
     * @Vich\UploadableField(mapping="modele_notice", fileNameProperty="notice")
     * @var File|null
     */
    private $noticeFile;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixNeuf;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixRecon;

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

    public function getMarque(): ?Marques
    {
        return $this->marque;
    }

    public function setMarque(?Marques $marque): self
    {
        $this->marque = $marque;

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
            $materiel->setModele($this);
        }

        return $this;
    }

    public function removeMateriel(Materiels $materiel): self
    {
        if ($this->materiels->contains($materiel)) {
            $this->materiels->removeElement($materiel);
            // set the owning side to null (unless already changed)
            if ($materiel->getModele() === $this) {
                $materiel->setModele(null);
            }
        }

        return $this;
    }

    public function getType(): ?Types
    {
        return $this->type;
    }

    public function setType(?Types $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPilote(): ?string
    {
        return $this->pilote;
    }

    public function setPilote(?string $pilote): self
    {
        $this->pilote = $pilote;

        return $this;
    }

    public function getNotice(): ?string
    {
        return $this->notice;
    }

    public function setNotice(string $notice): self
    {
        $this->notice = $notice;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $noticeFile
     */
    public function setNoticeFile(?File $noticeFile = null): void
    {
        $this->noticeFile = $noticeFile;

        if (null !== $noticeFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getNoticeFile(): ?File
    {
        return $this->noticeFile;
    }

    /**
     * @param null|\Symfony\Component\HttpFoundation\File\File $piloteFile
     */
    public function setPiloteFile(?File $piloteFile = null): void
    {
        $this->piloteFile = $piloteFile;

        if (null !== $piloteFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getPiloteFile(): ?File
    {
        return $this->piloteFile;
    }
    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(?\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getPrixNeuf(): ?float
    {
        return $this->prixNeuf;
    }

    public function setPrixNeuf(?float $prixNeuf): self
    {
        $this->prixNeuf = $prixNeuf;

        return $this;
    }

    public function getPrixRecon(): ?float
    {
        return $this->prixRecon;
    }

    public function setPrixRecon(?float $prixRecon): self
    {
        $this->prixRecon = $prixRecon;

        return $this;
    }
}
