<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Idee
 * @package App\Entity
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

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getNomIdee(): ?string
    {
        return $this->nom_idee;
    }

    /**
     * @param string $nom_idee
     * @return Idee
     */
    public function setNomIdee(string $nom_idee): self
    {
        $this->nom_idee = $nom_idee;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescriptionIdee(): ?string
    {
        return $this->description_idee;
    }

    /**
     * @param string $description_idee
     * @return Idee
     */
    public function setDescriptionIdee(string $description_idee): self
    {
        $this->description_idee = $description_idee;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    /**
     * @param int $id_user
     * @return Idee
     */
    public function setIdUser(int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    /**
     * @param string $lieu
     * @return Idee
     */
    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }
}
