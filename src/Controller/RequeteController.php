<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Curl;

class RequeteController extends AbstractController
{

        /**
         *
         * TODO penser à rajouter le parametre de token
         */

        /**
         * EVENEMENT
         */
        public function ajouterEvenement($data, Curl $crl)
        {
                $response = $crl->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/evenement/ajouter", "application/javascript", $data);

                echo $response;
        }

        public function recupererEvenement($data = null, Curl $crl)
        {
                $data = $data ? $data : "";

                $query = $crl->faireRequeteAvecHeader("GET", "http://10.131.129.13:5000/api/evenement/recuperer", "application/javascript", $data);
        }



        /**+
         * IDEE
         */

        public function ajouterIdee($data, Curl $crl)
        {
                $response = $crl->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/idee/ajouter", "application/javascript", $data);
        }

        public function recupererIdee($data, Curl $crl)
        {
                $data = $data ? $data : "";
                $query = $crl->faireRequeteAvecHeader("GET", "http://10.131.129.13:5000/api/idee/recuperer", "application/javascript", $data);
        }


        /*

        public function ajouterUtilisateur($data, Curl $crl)
        {
                $response = $crl->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/utilisateur/ajouter", "application/javascript", $data);
        }


        public function connexionUtilisateur($data)
        {
                return $response = $crl->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/utilisateur/ajouter", "application/javascript", $data);

        }

         */




        /**
         * ACHETER
         */

        public function acheter($data)
        {
                return $response = $crl->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/achete/ajouter", "application/javascript", $data);
        }

        public function recupererAchats()
        {
                return $response = $crl->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/achete/ajouter", "application/javascript", $data);
        }




        public function recupererProduit($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/achate/recuperer/{$id}", 'application/javascript');
        }

        public function recupererAcheteur($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/achate/recuperer/{$id}", 'application/javascript');
        }

        /***
         * AIME
         */

        public function recupererTousLesJaime()
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/aime/recuperer/", 'application/javascript');
        }


        public function recupererPhotoAimee($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/aime/recuperer/{$id}", 'application/javascript');
        }

        public function recupererUtilisateurAimant($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/aime/recuperer/{$id}", 'application/javascript');
        }

        public function publierUnLikeSurPhoto($data)
        {
                return $response = $crl->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/achete/ajouter", "application/javascript", $data);
        }


        /**
         * AIME_IDEE
         */


        public function recupererToutesLesEventAimee()
        {
                return $response = $crl->faireRequeteAvecHeader("GET", "http://10.131.129.13:5000/api/aime_idee/ajouter", "application/javascript");
        }

        public function recupererUtilisateurAimeIdeeEvent($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/aime_idee/recuperer/{$id}", 'application/javascript');
        }

        public function recupererEventIdeeAime($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/aime_idee/recuperer/{$id}", 'application/javascript');
        }

        public function publierUnLikeSurEventIdee($data)
        {
                return $response = $crl->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/aime_idee/ajouter", "application/javascript", $data);
        }


        /**
         * COMMENTAIRE
         */


        public function recupereCommentaire()
        {
                return $response = $crl->faireRequeteAvecHeader("GET", "http://10.131.129.13:5000/api/commentaire/recuperer", "application/javascript");
        }

        public function recupererCommentaireParId($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/commentaire/recuperer/{$id}", 'application/javascript');
        }




        /**
         * LIEU
         * 
         */


        public function recupererTousLesLieus()
        {
                return $response = $crl->faireRequeteAvecHeader("GET", "http://10.131.129.13:5000/api/lieu/recuperer", "application/javascript");
        }


        public function recupererLieusParId($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/lieu/recuperer/{$id}", 'application/javascript');
        }


        /**
         * NOTIFIE
         */



        public function recupererToutesLesNotifS()
        {
                return $response = $crl->faireRequeteAvecHeader("GET", "http://10.131.129.13:5000/api/notif/recuperer", "application/javascript");
        }

        public function recupererIdeeNotif($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/notif/recuperer/{$id}", 'application/javascript');
        }



        public function recupererUtilisateurNotif($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/notif/recuperer/{$id}", 'application/javascript');
        }

        public function publierUnUtilisateurANotifie($data)
        {
                return $response = $crl->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/notif/ajouter", "application/javascript", $data);
        }



        /**
         * PARTICIPANT
         */


        public function recupererToutesParticipation()
        {
                return $response = $crl->faireRequeteAvecHeader("GET", "http://10.131.129.13:5000/api/participation/recuperer", "application/javascript");
        }


        public function recupererUilisateurParticipant($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/participation/recuperer/{$id}", 'application/javascript');
        }


        public function recupererEvenementParticipe($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/participation/recuperer/{$id}", 'application/javascript');
        }



        /**
         * PHOTO
         */



        public function recupererToutesLesPhotos()
        {
                return $response = $crl->faireRequeteAvecHeader("GET", "http://10.131.129.13:5000/api/photo/recuperer", "application/javascript");
        }

        public function recupererPhotoParId($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/photo/recuperer/{$id}", 'application/javascript');
        }



        public function ajouterPhoto($data)
        {
                return $response = $crl->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/photo/ajouter", "application/javascript", $data);
        }


        /**
         * PRODUIT
         */



        public function recupererTousLesProduits()
        {
                return $response = $crl->faireRequeteAvecHeader("GET", "http://10.131.129.13:5000/api/produit/recuperer", "application/javascript");
        }

        public function recupererProduitParId($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/produit/recuperer/{$id}", 'application/javascript');
        }

        public function ajouterProduit($data)
        {
                return $response = $crl->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/produit/ajouter", "application/javascript", $data);
        }




        /**
         * 
         * UTILISATEUR
         */


        public function recupererTousLesUtilisateur()
        {
                return $response = $crl->faireRequeteAvecHeader("GET", "http://10.131.129.13:5000/api/utilisateur/recuperer", "application/javascript");
        }

        public function recupererUtilisateurParId($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/utilisateur/recuperer/{$id}", 'application/javascript');
        }

        public function recupererUtilisateurParMail($id)
        {
                return $response = $crl->faireRequeteAvecHeader('GET', "http://10.131.129.13:5000/api/utilisateur/recuperer/{$id}", 'application/javascript');
        }


        public function ajouterUtilisateur($data)
        {
                return $response = $crl->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/utilisateur/ajouter", "application/javascript", $data);
        }

        public function connexionUtilisateur($data)
        {
                return $response = $crl->faireRequete("POST", "http://10.131.129.13:5000/api/utilisateur/connexion", "application/javascript", $data);
        }







}



