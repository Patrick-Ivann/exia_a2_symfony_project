<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IdeeRepository")
 */
class Idee
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
    private $nom_idee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description_idee;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieu;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomIdee(): ?string
    {
        return $this->nom_idee;
    }

    public function setNomIdee(string $nom_idee): self
    {
        $this->nom_idee = $nom_idee;

        return $this;
    }

    public function getDescriptionIdee(): ?string
    {
        return $this->description_idee;
    }

    public function setDescriptionIdee(string $description_idee): self
    {
        $this->description_idee = $description_idee;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }
}
