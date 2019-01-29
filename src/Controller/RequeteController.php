<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\services\Curl;

/**
 * Class RequeteController
 * @package App\Controller
 */
class RequeteController extends AbstractController
{

    /**
     * REST API IP address
     * @var string
     */
    private $ip = "10.131.129.156";

    /**
     * REST API port
     * @var string
     */
    private $port = "5000";

    /**
     * @param $data
     * @param Curl $crl
     */
    public function ajouterEvenement($data, Curl $crl)
    {
        $response = $crl->faireRequeteAvecHeader("POST", "http://" . $this->ip . ":" . $this->port . "/api/evenement/ajouter", "application/javascript", $data);
        echo $response;
    }

    /**
     * @param null $data
     * @param Curl $crl
     * @return mixed
     */
    public function recupererEvenement($data = null, Curl $crl)
    {
        $data = $data ? $data : "";
        return $query = $crl->faireRequeteAvecHeader("GET", "http://" . $this->ip . ":5000/api/evenement/recuperer", "application/javascript", $data);
    }

    /**
     * @param $id
     * @param Curl $crl
     * @return mixed
     */
    public function recupererEvenementParId($id, Curl $crl)
    {
        return $query = $crl->faireRequeteAvecHeader("GET", "http://" . $this->ip . ":5000/api/evenement/recuperer/{$id}", "application/javascript");
    }

    /**
     * @param $id
     * @param Curl $crl
     * @return mixed
     */
    public function supprimerEvenement($id, Curl $crl)
    {
        return $query = $crl->faireRequeteAvecHeader("DELETE", "http://" . $this->ip . ":5000/api/evenement/supprimer/{$id}", "application/javascript");
    }

    /**
     * @param $data
     * @param Curl $crl
     */
    public function ajouterIdee($data, Curl $crl)
    {
        $response = $crl->faireRequeteAvecHeader("POST", "http://" . $this->ip . ":5000/api/idee/ajouter", "application/javascript", $data);
    }

    /**
     * @param Curl $crl
     * @param null $data
     * @return mixed
     */
    public function recupererIdee(Curl $crl, $data = null)
    {
        $data = $data ? $data : "";
        return $query = $crl->faireRequeteAvecHeader("GET", "http://" . $this->ip . ":5000/api/idee/recuperer", "application/javascript", $data);
    }

    /**
     * @param $data
     * @param Curl $crl
     */
    public function ajouterUtilisateur($data, Curl $crl)
    {
        $response = $crl->faireRequeteAvecHeader("POST", "http://" . $this->ip . ":5000/api/utilisateur/ajouter", "application/javascript", $data);
    }

    /**
     * @param $data
     * @param $crl
     * @return mixed
     */
    public function connexionUtilisateur($data, $crl)
    {
        return $response = $crl->faireRequeteAvecHeader("POST", "http://" . $this->ip . ":5000/api/utilisateur/connexion", "application/javascript", $data);
    }

    /**
     * @param Curl $curl
     * @return mixed
     */
    public function recupererTousLesJaime(curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/aime/recuperer/", 'application/javascript');
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererPhotoAimee($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/aime/recuperer/photo/{$id}", 'application/javascript');
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererUtilisateurAimant($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/aime/recuperer/{$id}", 'application/javascript');
    }

    /**
     * @param $data
     * @param Curl $curl
     * @return mixed
     */
    public function publierUnLikeSurPhoto($data, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("POST", "http://" . $this->ip . ":5000/api/aime/ajouter", "application/javascript", $data);
    }

    /**
     * @param $data
     * @param Curl $curl
     * @return mixed
     */
    public function acheter($data, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("POST", "http://" . $this->ip . ":5000/api/achete/ajouter", "application/javascript", $data);
    }

    /**
     * @param Curl $curl
     * @return mixed
     */
    public function recupererAchats(curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("POST", "http://" . $this->ip . ":5000/api/achete/ajouter", "application/javascript");
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererProduit($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/achete/recuperer/{$id}", 'application/javascript');
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererAcheteur($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/achete/recuperer/{$id}", 'application/javascript');
    }

    /**
     * @param Curl $curl
     * @return mixed
     */
    public function recupererProduitLesPlusVendus(curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/achete/recuperer/produit/top", 'application/javascript');
    }

    /**
     * @param Curl $curl
     * @return mixed
     */
    public function recupererToutesLesEventAimee(curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("GET", "http://" . $this->ip . ":5000/api/aime_idee/ajouter", "application/javascript");
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererUtilisateurAimeIdeeEvent($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/aime_idee/recuperer/{$id}", 'application/javascript');
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererEventIdeeAime($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/aime_idee/recuperer/event/{$id}", 'application/javascript');
    }

    /**
     * @param $data
     * @param Curl $curl
     * @return mixed
     */
    public function publierUnLikeSurEventIdee($data, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("POST", "http://" . $this->ip . ":5000/api/aime_idee/ajouter", "application/javascript", $data);
    }

    /**
     * @param Curl $curl
     * @return mixed
     */
    public function recupereCommentaire(curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("GET", "http://" . $this->ip . ":5000/api/commentaire/recuperer", "application/javascript");
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererCommentaireParId($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/commentaire/recuperer/{$id}", 'application/javascript');
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererCommentaireParIdPhoto($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/commentaire/recuperer/photo/{$id}", 'application/javascript');
    }

    /**
     * @param $data
     * @param Curl $curl
     * @return mixed
     */
    public function ajouterCommentaire($data, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("POST", "http://" . $this->ip . ":5000/api/commentaire/ajouter", "application/javascript", $data);
    }

    /**
     * @param Curl $curl
     * @return mixed
     */
    public function recupererTousLesLieus(curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("GET", "http://" . $this->ip . ":5000/api/lieu/recuperer", "application/javascript");
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererLieusParId($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/lieu/recuperer/{$id}", 'application/javascript');
    }

    /**
     * @param Curl $curl
     * @return mixed
     */
    function recupererToutesLesNotifS(curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("GET", "http://" . $this->ip . ":5000/api/notifie/recuperer", "application/javascript");
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererIdeeNotif($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/notifie/recuperer/{$id}", 'application/javascript');
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererUtilisateurNotif($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/notifie/recuperer/utilisateur/{$id}", 'application/javascript');
    }

    /**
     * @param $data
     * @param Curl $curl
     * @return mixed
     */
    public function publierUnUtilisateurANotifie($data, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("POST", "http://" . $this->ip . ":5000/api/notifie/ajouter", "application/javascript", $data);
    }

    /**
     * @param Curl $curl
     * @return mixed
     */
    public function recupererToutesParticipation(curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("GET", "http://" . $this->ip . ":5000/api/participer/recuperer", "application/javascript");
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererUilisateurParticipant($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/participer/recuperer/{$id}", 'application/javascript');
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererEvenementParticipe($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/participer/recuperer/event/{$id}", 'application/javascript');
    }

    /**
     * @param $data
     * @param Curl $curl
     * @return mixed
     */
    public function participerUnEvenementParId($data, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("POST", "http://" . $this->ip . ":5000/api/participer/ajouter", "application/javascript", $data);

    }

    /**
     * @param Curl $curl
     * @return mixed
     */
    public function recupererToutesLesPhotos(curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("GET", "http://" . $this->ip . ":5000/api/photo/recuperer", "application/javascript");
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererPhotoParId($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/photo/recuperer/{$id}", 'application/javascript');
    }

    /**
     * @param $id
     * @param Curl $crl
     * @return mixed
     */
    public function recupererPhotoParIdEvent($id, Curl $crl)
    {
        return $query = $crl->faireRequeteAvecHeader("GET", "http://" . $this->ip . ":5000/api/photo/recuperer/evenement/{$id}", "application/javascript");
    }

    /**
     * @param $data
     * @param $path
     * @param $type
     * @param Curl $curl
     */
    public function ajouterPhoto($data, $path, $type, curl $curl)
    {
        $response = $curl->faireRequeteAvecFichier("POST", "http://" . $this->ip . ":5000/api/photo/ajouter", "application/javascript", $data, $path, $type);
    }

    /**
     * @param $data
     * @param Curl $curl
     * @return mixed
     */
    public function signalerUnePhotoParId($data, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("POST", "http://" . $this->ip . ":5000/api/signaler/ajouter", "application/javascript", $data);
    }

    /**
     * @param Curl $curl
     * @return mixed
     */
    public function recupererTousLesProduits(curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("GET", "http://" . $this->ip . ":5000/api/produit/recuperer", "application/javascript");
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererProduitParId($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/produit/recuperer/{$id}", 'application/javascript');
    }

    /**
     * @param $data
     * @param $path
     * @param $type
     * @param Curl $curl
     */
    public function ajouterProduit($data, $path, $type, curl $curl)
    {
        $response = $curl->faireRequeteAvecFichier("POST", "http://" . $this->ip . ":5000/api/produit/ajouter", "application/javascript", $data, $path, $type);
        echo $response;
    }

    /**
     * @param $id
     * @param Curl $crl
     * @return mixed
     */
    public function supprimerProduit($id, Curl $crl)
    {
        return $query = $crl->faireRequeteAvecHeader("DELETE", "http://" . $this->ip . ":5000/api/produit/supprimer/{$id}", "application/javascript");
    }

    /**
     * @param Curl $curl
     * @return mixed
     */
    public function recupererTousLesUtilisateur(curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader("GET", "http://".$this->ip.":5000/api/utilisateur/recuperer", "application/javascript");
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererUtilisateurParId($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://" . $this->ip . ":5000/api/utilisateur/recuperer/{$id}", 'application/javascript');
    }

    /**
     * @param $id
     * @param Curl $curl
     * @return mixed
     */
    public function recupererUtilisateurParMail($id, curl $curl)
    {
        return $response = $curl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/utilisateur/recuperer/{$id}", 'application/javascript');
    }
}