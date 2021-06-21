<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

//    /**
//     * @ORM\Column(type="integer")
//     */
//    private $idTopic;
//
//    /**
//     * @ORM\Column(type="integer")
//     */
//    private $idUser;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publicationDate;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Topic::class, inversedBy="messages")
     */
    private $idTopic;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     */
    private $idUser;

  

    public function getId(): ?int
    {
        return $this->id;
    }
//
//    public function getIdTopic(): ?int
//    {
//        return $this->idTopic;
//    }
//
//    public function setIdTopic(int $idTopic): self
//    {
//        $this->idTopic = $idTopic;
//
//        return $this;
//    }
//
//    public function getIdUser(): ?int
//    {
//        return $this->idUser;
//    }
//
//    public function setIdUser(int $idUser): self
//    {
//        $this->idUser = $idUser;
//
//        return $this;
//    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
    public function __toString() {
        return $this->content;
    }

    public function getIdTopic(): ?Topic
    {
        return $this->idTopic;
    }

    public function setIdTopic(?Topic $idTopic): self
    {
        $this->idTopic = $idTopic;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }


}
