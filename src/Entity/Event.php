<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * Class Event
 * @package App\Entity
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
    public function getNomEvent(): ?string
    {
        return $this->nom_event;
    }

    /**
     * @param string $nom_event
     * @return Event
     */
    public function setNomEvent(string $nom_event): self
    {
        $this->nom_event = $nom_event;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDateDebutEvent(): ?string
    {
        return $this->date_debut_event;
    }

    /**
     * @param string $date_debut_event
     * @return Event
     */
    public function setDateDebutEvent(string $date_debut_event): self
    {
        $this->date_debut_event = $date_debut_event;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDateFinEvent(): ?string
    {
        return $this->date_fin_event;
    }

    /**
     * @param string $date_fin_event
     * @return Event
     */
    public function setDateFinEvent(string $date_fin_event): self
    {
        $this->date_fin_event = $date_fin_event;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNomLieu(): ?string
    {
        return $this->nom_lieu;
    }

    /**
     * @param string $nom_lieu
     * @return Event
     */
    public function setNomLieu(string $nom_lieu): self
    {
        $this->nom_lieu = $nom_lieu;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTypeEvent(): ?int
    {
        return $this->type_event;
    }

    /**
     * @param int $type_event
     * @return Event
     */
    public function setTypeEvent(int $type_event): self
    {
        $this->type_event = $type_event;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPrix(): ?int
    {
        return $this->prix;
    }

    /**
     * @param int $prix
     * @return Event
     */
    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

}
