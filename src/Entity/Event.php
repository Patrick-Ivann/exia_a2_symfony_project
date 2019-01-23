<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
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
    private $nom_event;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $date_debut_event;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $date_fin_event;

    /**
     * @ORM\Column(type="string")
     */
    private $nom_lieu;

    /**
     * @ORM\Column(type="integer")
     */
    private $type_event;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEvent(): ?string
    {
        return $this->nom_event;
    }

    public function setNomEvent(string $nom_event): self
    {
        $this->nom_event = $nom_event;

        return $this;
    }

    public function getDateDebutEvent(): ?string
    {
        return $this->date_debut_event;
    }

    public function setDateDebutEvent(string $date_debut_event): self
    {
        $this->date_debut_event = $date_debut_event;

        return $this;
    }

    public function getDateFinEvent(): ?string
    {
        return $this->date_fin_event;
    }

    public function setDateFinEvent(string $date_fin_event): self
    {
        $this->date_fin_event = $date_fin_event;

        return $this;
    }

    public function getNomLieu(): ?string
    {
        return $this->nom_lieu;
    }

    public function setNomLieu(string $nom_lieu): self
    {
        $this->nom_lieu = $nom_lieu;

        return $this;
    }

    public function getTypeEvent(): ?int
    {
        return $this->type_event;
    }

    public function setTypeEvent(int $type_event): self
    {
        $this->type_event = $type_event;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

}
