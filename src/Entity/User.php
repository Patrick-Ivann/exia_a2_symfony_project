<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
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
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email
     */
    private $adresse_mail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/")
     */
    private $mot_de_passe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mot_de_passe_verif;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\File(maxSize="6000000", mimeTypes = {"image/png", "image/jpg" , "image/jpeg"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_lieu;

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
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     * @return User
     */
    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return User
     */
    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAdresseMail(): ?string
    {
        return $this->adresse_mail;
    }

    /**
     * @param string $adresse_mail
     * @return User
     */
    public function setAdresseMail(string $adresse_mail): self
    {
        $this->adresse_mail = $adresse_mail;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMotDePasse(): ?string
    {
        return $this->mot_de_passe;
    }

    /**
     * @param string $mot_de_passe
     * @return User
     */
    public function setMotDePasse(string $mot_de_passe): self
    {
        $this->mot_de_passe = $mot_de_passe;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMotDePasseVerif(): ?string
    {
        return $this->mot_de_passe_verif;
    }

    /**
     * @param string $mot_de_passe_verif
     * @return User
     */
    public function setMotDePasseVerif(string $mot_de_passe_verif): self
    {
        $this->mot_de_passe_verif = $mot_de_passe_verif;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return User
     */
    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;
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
     * @return User
     */
    public function setNomLieu(string $nom_lieu): self
    {
        $this->nom_lieu = $nom_lieu;
        return $this;
    }
}