<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 */
class Photo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $legende_photo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\File(maxSize="6000000", mimeTypes = {"image/png", "image/jpg" , "image/jpeg"}    )
     */
    private $file_photo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLegendePhoto(): ?string
    {
        return $this->legende_photo;
    }

    public function setLegendePhoto(string $legende_photo): self
    {
        $this->legende_photo = $legende_photo;

        return $this;
    }

    public function getFilePhoto()
    {
        return $this->file_photo;
    }

    public function setFilePhoto($file_photo): self
    {
        $this->file_photo = $file_photo;

        return $this;
    }
}
