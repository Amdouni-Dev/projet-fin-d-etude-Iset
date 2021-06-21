<?php

namespace App\Entity;

use App\Repository\MutimediaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MutimediaRepository::class)
 */
class Mutimedia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true,name="source")
     */
    private $source;

    /**
     * @ORM\ManyToOne(targetEntity=Publication::class, inversedBy="mutimedia")
     */
    private $publication;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getPublication(): ?publication
    {
        return $this->publication;
    }

    public function setPublication(?publication $publication): self
    {
        $this->publication = $publication;

        return $this;
    }

    public function __toString():string
    {
        return $this->getSource();
    }

}
