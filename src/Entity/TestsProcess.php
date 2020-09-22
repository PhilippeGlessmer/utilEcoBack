<?php

namespace App\Entity;

use App\Repository\TestsProcessRepository;
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
 * @ORM\Entity(repositoryClass=TestsProcessRepository::class)
 * @ApiResource()
 */
class TestsProcess
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Materiels::class, inversedBy="testsProcesses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $materiel;

    /**
     * @ORM\ManyToOne(targetEntity=Proccess::class, inversedBy="testsProcess")
     * @ORM\JoinColumn(nullable=true)
     */
    private $proccess;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $resultat;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="testsProcesses")
     * @ORM\JoinColumn(nullable=true)
     */
    private $User;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMateriel(): ?Materiels
    {
        return $this->materiel;
    }

    public function setMateriel(?Materiels $materiel): self
    {
        $this->materiel = $materiel;

        return $this;
    }

    public function getProccess(): ?Proccess
    {
        return $this->proccess;
    }

    public function setProccess(Proccess $proccess): self
    {
        $this->proccess = $proccess;

        return $this;
    }

    public function getResultat(): ?string
    {
        return $this->resultat;
    }

    public function setResultat(string $resultat): self
    {
        $this->resultat = $resultat;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->User;
    }

    public function setUser(?Users $User): self
    {
        $this->User = $User;

        return $this;
    }
}
