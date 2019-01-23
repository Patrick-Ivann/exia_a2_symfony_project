<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexteCommentaire(): ?string
    {
        return $this->texte_commentaire;
    }

    public function setTexteCommentaire(string $texte_commentaire): self
    {
        $this->texte_commentaire = $texte_commentaire;

        return $this;
    }
}
