<?php

namespace App\Entity;

use App\Repository\OpportuniteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OpportuniteRepository::class)
 */
class Opportunite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateLimite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $domaineConcerne;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lienFormPostul;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $TypeOffre;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="opportunites")
     */
    private $lanceur;

    /**
     * @ORM\ManyToOne(targetEntity=Association::class, inversedBy="opportunites")
     */
    private $association;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isValid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

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

    public function getDateLimite(): ?\DateTimeInterface
    {
        return $this->dateLimite;
    }

    public function setDateLimite(\DateTimeInterface $dateLimite): self
    {
        $this->dateLimite = $dateLimite;

        return $this;
    }
    public function setPublicationDate(\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }
    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getDomaineConcerne(): ?string
    {
        return $this->domaineConcerne;
    }

    public function setDomaineConcerne(?string $domaineConcerne): self
    {
        $this->domaineConcerne = $domaineConcerne;

        return $this;
    }

    public function getLienFormPostul(): ?string
    {
        return $this->lienFormPostul;
    }

    public function setLienFormPostul(?string $lienFormPostul): self
    {
        $this->lienFormPostul = $lienFormPostul;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTypeOffre(): ?string
    {
        return $this->TypeOffre;
    }

    public function setTypeOffre(?string $TypeOffre): self
    {
        $this->TypeOffre = $TypeOffre;

        return $this;
    }

    public function getLanceur(): ?User
    {
        return $this->lanceur;
    }

    public function setLanceur(?User $lanceur): self
    {
        $this->lanceur = $lanceur;

        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): self
    {
        $this->association = $association;

        return $this;
    }
    public function __toString() {
        return $this->getTitre();
    }

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(?bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }
    public function isValid(): ?bool
    {
        return $this->isValid;
    }

}
