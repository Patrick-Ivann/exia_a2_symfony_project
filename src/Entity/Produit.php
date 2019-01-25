<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 */
class Produit
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
    private $nom_produit;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix_produit;

    /**
     * @ORM\Column(type="string")
     * @Assert\File(maxSize="6000000", mimeTypes = {"application/png", "application/jpg"})
     */
    private $photo_produit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nom_produit;
    }

    public function setNomProduit(string $nom_produit): self
    {
        $this->nom_produit = $nom_produit;

        return $this;
    }

    public function getPrixProduit(): ?int
    {
        return $this->prix_produit;
    }

    public function setPrixProduit(int $prix_produit): self
    {
        $this->prix_produit = $prix_produit;

        return $this;
    }

    public function getPhotoProduit()
    {
        return $this->photo_produit;
    }

    public function setPhotoProduit($photo_produit) : self
    {
        $this->$photo_produit = $photo_produit;

        return $this;
    }
}
