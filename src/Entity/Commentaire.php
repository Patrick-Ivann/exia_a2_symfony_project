<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Commentaire
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CommentaireRepository")
 */
class Commentaire
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
    private $texte_commentaire;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Commentaire
     */
    public function setId(int $id):self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTexteCommentaire(): ?string
    {
        return $this->texte_commentaire;
    }

    /**
     * @param string $texte_commentaire
     * @return Commentaire
     */
    public function setTexteCommentaire(string $texte_commentaire): self
    {
        $this->texte_commentaire = $texte_commentaire;
        return $this;
    }
}
