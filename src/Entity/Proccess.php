<?php

namespace App\Entity;

use App\Repository\ProccessRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ProccessRepository::class)
 * @Vich\Uploadable
 * @ApiResource()
 */
class Proccess
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Types::class, inversedBy="procedures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="text")
     */
    private $action;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $resultNegatif;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $resultPositif;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordre;

    /**
     * @ORM\OneToOne(targetEntity=TestsProcess::class, mappedBy="proccess", cascade={"persist", "remove"})
     */
    private $testsProcess;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="proccess_image", fileNameProperty="image")
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getResultNegatif(): ?int
    {
        return $this->resultNegatif;
    }

    public function setResultNegatif(int $resultNegatif): self
    {
        $this->resultNegatif = $resultNegatif;

        return $this;
    }

    public function getResultPositif(): ?int
    {
        return $this->resultPositif;
    }

    public function setResultPositif(?int $resultPositif): self
    {
        $this->resultPositif = $resultPositif;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getTestsProcess(): ?TestsProcess
    {
        return $this->testsProcess;
    }

    public function setTestsProcess(TestsProcess $testsProcess): self
    {
        $this->testsProcess = $testsProcess;

        // set the owning side of the relation if necessary
        if ($testsProcess->getProccess() !== $this) {
            $testsProcess->setProccess($this);
        }

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
}
