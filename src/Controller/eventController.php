<?php
namespace App\Controller;
use App\Entity\Commentaire;
use App\Entity\Event;
use App\Entity\Photo;
use App\Form\CommentaireFormType;
use App\Form\EventFormType;
use App\Form\PhotoFormType;
use App\Controller\RequeteController;
use App\services\Curl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
class eventController extends AbstractController
{
    /**
     * @Route("/events/add")
     * @param Request $req
     * @param \App\Controller\RequeteController $rctrl
     * @param Curl $crl
     * @return string|Response
     */
    public function add(Request $req, RequeteController $rctrl, Curl $crl)
    {
        $event = new Event();

        $form = $this->createForm(EventFormType::class, $event);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            $eventData = $form->getData();

            $eventDataToSend = json_encode([
                'nom_event' => $eventData->getNomEvent(),
                'date_debut_event' => $eventData->getDateDebutEvent(),
                'date_fin_event' => $eventData->getDateFinEvent(),
                'nom_lieu' => $eventData->getNomLieu(),
                'type_event' => $eventData->getTypeEvent(),
                'prix' => $eventData->getPrix()
            ]);

            $rctrl->ajouterEvenement($eventDataToSend, $crl);
        }
        try {
            return $this->render('eventCreate.html.twig', [
                'form' => $form->createView()
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @Route("/events" , name="events"))
     * @param \App\Controller\RequeteController $rctrl
     * @param Curl $crl
     * @return string|Response
     */
    public function display(RequeteController $rctrl, Curl $crl)
    {
        $events = $rctrl->recupererEvenement(null, $crl);

        $eventToDisplay = json_decode($events);

        if (is_object($eventToDisplay)) {
            $events = '[' . $events . ']';
            $eventToDisplay = json_decode($events);
        }

        try {
            return $this->render('eventDisplay.html.twig', [
                'events' => $eventToDisplay
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @Route("/deleteShop/{id_event}" , name="deleteShop")
     * @param \App\Controller\RequeteController $rctrl
     * @param Curl $crl
     * @return string|Response
     */
    public function delete($id_event, RequeteController $rctrl, Curl $crl)
    {
        $rctrl->supprimerEvenement($id_event, $crl);
        return $this->redirectToRoute("displayEvent");
    }

    /**
     * @Route("/event/{id_event}" , name="eventById")
     * @param \App\Controller\RequeteController $rctrl
     * @param Curl $crl
     * @return string|Response
     */
    public function displayById($id_event, Request $req, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        $id_user = $session->get("id_user");

        $events = $rctrl->recupererEvenementParId($id_event, $crl);

        $eventToDisplay = json_decode($events);

        $photo = $rctrl->recupererPhotoParIdEvent($id_event, $crl);

        $photoToDisplay = json_decode($photo);

        $commentaire = [];

        if($photo) {

            foreach ($photoToDisplay as $key=>$photo)
            {
                $commentaire[] = $rctrl->recupererCommentaireParIdPhoto($photo->{"id_photo"}, $crl);
            }
        }
        /*
            Faire le traitement pour choper l'image et son nom
         */

        //Form en cas d'ajout photo
        $formPhoto = $this->createFormPhoto($id_event, $req, $rctrl, $crl, $id_user);


        try {
            return $this->render('eventDisplayID.html.twig', [
                'event' => $eventToDisplay,
                'photo' => $photoToDisplay,
                'commentaire' => $commentaire,
                'formPhoto' => $formPhoto->createView(),

            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    function createFormPhoto($id_event, $req, $rctrl, $crl, $id_user)
    {
        $photo = new Photo();

        $formPhoto = $this->createForm(PhotoFormType::class, $photo);

        $formPhoto->handleRequest($req);

        if ($formPhoto->isSubmitted() && $formPhoto->isValid()) {

            $photoData = $formPhoto->getData();

            $photoDataToSend = json_encode([
                'legende_photo' => $photoData->getLegendePhoto(),
                'id_user' => $id_user,
                'id_event' => $id_event
            ]);

            //foutre id_user de session
            $file = $req->files->get("photo_form")["file_photo"];

            $type = 'photo';

            $rctrl->ajouterPhoto($photoDataToSend, $file, $type, $crl);
        }
        return $formPhoto;
    }

    /**
     * @Route("/photos/{id_photo}" , name="photoById")
     * @param \App\Controller\RequeteController $rctrl
     * @param Curl $crl
     * @return string|Response
     */
    public function photos($id_photo, Request $req, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        $id_user = $session->get("id_user");

        $photo = $rctrl->recupererPhotoParId($id_photo, $crl);

        $commentaire = $rctrl->recupererCommentaireParIdPhoto($id_photo, $crl);

        $photoToDisplay = json_decode($photo);

        $commentaireToDisplay = json_decode($commentaire);

        $formComm = $this->createFormCommentaire($id_photo, $req, $rctrl, $crl, $id_user);

        if(is_array($commentaireToDisplay))
        {
            foreach ($commentaireToDisplay as $com){
                $com->name_user = json_decode($rctrl->recupererUtilisateurParId($com->id_user, $crl))->prenom;
                $alone = false;
            }
        }else if (is_object($commentaireToDisplay)){
            $commentaireToDisplay->name_user = json_decode($rctrl->recupererUtilisateurParId($commentaireToDisplay->id_user, $crl))->prenom;
            $alone = true;
        }

        dump($commentaireToDisplay);

        try {
            return $this->render('photoDisplayID.html.twig', [
                'photo' => $photoToDisplay,
                'commentaire' => $commentaireToDisplay,
                'formComm' => $formComm->createView(),
                'alone' => $alone
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }



    function createFormCommentaire($id_photo, $req, $rctrl, $crl, $id_user)
    {
        $comm = new Commentaire();

        $formComm = $this->createForm(CommentaireFormType::class, $comm);

        $formComm->handleRequest($req);

        if ($formComm->isSubmitted() && $formComm->isValid()) {

            $commentaireData = $formComm->getData();

            $CommentaireDataToSend = json_encode([
                'texte_commentaire' => $commentaireData->getTexteCommentaire(),
                'id_user' => $id_user,
                'id_photo' => $id_photo
            ]);

            //foutre id_user de session
            $rctrl->ajouterCommentaire($CommentaireDataToSend, $crl);

            $this->redirect($req->getUri());
        }
        return $formComm;
    }

    /**
     * @Route("/like/{id_photo}" , name="likeById")
     */
    function like($id_photo, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        $id_user = $session->get("id_user");

        $like = json_encode([
            'id_photo' => $id_photo,
            'id_user' => $id_user
        ]);

        $rctrl->publierUnLikeSurPhoto($like, $crl);

        return $this->redirectToRoute("events");
    }


    /**
     * @Route("/signale/{id_photo}" , name="signaleById")
     */
    function signale($id_photo, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        $id_user = $session->get("id_user");

        $signale = json_encode([
            'id_photo' => $id_photo,
            'id_user' => $id_user
        ]);


        //Creer cet requête
        $rctrl->signalerUnePhotoParId($signale, $crl);

        return $this->redirectToRoute("events");
    }

    /**
     * @Route("/participe/{id_event}" , name="participeById")
     */
    function participe($id_event, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        $id_user = $session->get("id_user");

        $participe = json_encode([
            'id_event' => $id_event,
            'id_user' => $id_user
        ]);

        $rctrl->participerUnEvenementParId($participe, $crl);

        return $this->redirectToRoute("events");
    }
}
?>
