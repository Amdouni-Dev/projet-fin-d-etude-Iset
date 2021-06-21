<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PublicationRepository::class)
 */
class Publication
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_pub;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $contenu_pub;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="publication",cascade={"persist", "remove"})
     */
    private $commentaires;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="publications")
     */
    private $user;

//    /**
//     * @ORM\ManyToOne(targetEntity=Administration::class, inversedBy="publications")
//     */
//    private $admin;

    /**
     * @ORM\OneToMany(targetEntity=Mutimedia::class, mappedBy="publication",cascade={"persist", "remove"})
     */
    private $mutimedia;

//    /**
//     * @ORM\Column(type="string", length=255, nullable=true)
//     */
//    private $nom;

//    /**
//     * @ORM\Column(type="integer", nullable=true)
//     */
//    private $age;
//
//    /**
//     * @ORM\Column(type="string", length=10, nullable=true)
//     */
//    private $sexe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $localisation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $archiver;

    /**
     * @return mixed
     */
    public function getArchiver()
    {
        return $this->archiver;
    }

    /**
     * @param mixed $archiver
     */
    public function setArchiver($archiver): void
    {
        $this->archiver = $archiver;
    }

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $latitude;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isValid;

    /**
     * @ORM\OneToMany(targetEntity=Activite::class, mappedBy="publication")
     */
    private $activites;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isResolved;
    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->mutimedia = new ArrayCollection();
        $this->activites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePub(): ?\DateTimeInterface
    {
        return $this->date_pub;
    }

    public function setDatePub(?\DateTimeInterface $date_pub): self
    {
        $this->date_pub = $date_pub;

        return $this;
    }

    public function getContenuPub(): ?string
    {
        return $this->contenu_pub;
    }

    public function setContenuPub(?string $contenu_pub): self
    {
        $this->contenu_pub = $contenu_pub;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setPublication($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPublication() === $this) {
                $commentaire->setPublication(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user= $user;

        return $this;
    }
//
//    public function getAdmin(): ?administration
//    {
//        return $this->admin;
//    }
//
//    public function setAdmin(?administration $admin): self
//    {
//        $this->admin = $admin;
//
//        return $this;
//    }

    /**
     * @return Collection|Mutimedia[]
     */
    public function getMutimedia(): Collection
    {
        return $this->mutimedia;
    }

    public function addMutimedia(Mutimedia $mutimedia): self
    {
        if (!$this->mutimedia->contains($mutimedia)) {
            $this->mutimedia[] = $mutimedia;
            $mutimedia->setPublication($this);
        }

        return $this;
    }

    public function removeMutimedia(Mutimedia $mutimedia): self
    {
        if ($this->mutimedia->removeElement($mutimedia)) {
            // set the owning side to null (unless already changed)
            if ($mutimedia->getPublication() === $this) {
                $mutimedia->setPublication(null);
            }
        }

        return $this;
    }
//
//    public function getNom(): ?string
//    {
//        return $this->nom;
//    }
//
//    public function setNom(?string $nom): self
//    {
//        $this->nom = $nom;
//
//        return $this;
//    }
//
//    public function getAge(): ?int
//    {
//        return $this->age;
//    }
//
//    public function setAge(?int $age): self
//    {
//        $this->age = $age;
//
//        return $this;
//    }
//
//    public function getSexe(): ?string
//    {
//        return $this->sexe;
//    }
//
//    public function setSexe(?string $sexe): self
//    {
//        $this->sexe = $sexe;
//
//        return $this;
//    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

//    public function __toString():string
//    {
//        return $this->getMutimedia();
//    }

public function getLongitude(): ?string
{
    return $this->longitude;
}

public function setLongitude(?string $longitude): self
{
    $this->longitude = $longitude;

    return $this;
}

public function getLatitude(): ?string
{
    return $this->latitude;
}

public function setLatitude(?string $latitude): self
{
    $this->latitude = $latitude;

    return $this;
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

    /**
     * @return Collection|Activite[]
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activites->contains($activite)) {
            $this->activites[] = $activite;
            $activite->setPublication($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        if ($this->activites->removeElement($activite)) {
            // set the owning side to null (unless already changed)
            if ($activite->getPublication() === $this) {
                $activite->setPublication(null);
            }
        }

        return $this;
    }

    public function getIsResolved(): ?bool
    {
        return $this->isResolved;
    }

    public function setIsResolved(?bool $isResolved): self
    {
        $this->isResolved = $isResolved;

        return $this;
    }


}
