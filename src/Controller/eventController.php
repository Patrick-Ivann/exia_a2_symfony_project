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
class eventController extends AbstractController
{
    /**
     * @Route("/events/add")
     * @param Request $req
     * @param \App\Controller\RequeteController $requestController
     * @param Curl $crl
     * @return string|Response
     */
    public function add(Request $req, RequeteController $requestController, Curl $crl)
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
     * @Route("/events" , name="displayEvent"))
     * @param \App\Controller\RequeteController $requestController
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
    public function displayById($id_event, Request $req, RequeteController $rctrl, Curl $crl)
    {
        $events = $rctrl->recupererEvenementParId($id_event, $crl);

        $eventToDisplay = json_decode($events);

        $photo = $rctrl->recupererPhotoParIdEvent($id_event, $crl);

        $photoToDisplay = json_decode($photo);

        dump($photoToDisplay);

        foreach ($photoToDisplay as $photo)
        {

            $commentaire[] = $rctrl->recupererCommentaireParIdPhoto($photo->{"id_photo"}, $crl);

            $formComm[] = $this->createFormCommentaire(1, $req, $rctrl, $crl);

        }
/*
        foreach ($commentaire as $commentaire)
        {
            $commentaireToDisplay[] = json_decode($commentaire);
        }
        dump($commentaireToDisplay); */

        foreach($formComm as $form)
        {
            $formCommCreated[] = $form->createView();
        }

        dump($formCommCreated);
        dump($commentaire);
        /*
            Faire le traitement pour choper l'image et son nom
         */

        //Form en cas d'ajout photo
        $formPhoto = $this->createFormPhoto($id_event, $req, $rctrl, $crl);

        //Form en cas d'ajout de commentaire

        try {
            return $this->render('eventDisplayID.html.twig', [
                'event' => $eventToDisplay,
                'photo' => $photoToDisplay,
                'commentaire' => $commentaire,
                'formPhoto' => $formPhoto->createView(),
                'formCommCreated' => $formCommCreated
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    function createFormPhoto($id_event, $req, $rctrl, $crl)
    {
        $photo = new Photo();

        $formPhoto = $this->createForm(PhotoFormType::class, $photo);

        $formPhoto->handleRequest($req);

        if ($formPhoto->isSubmitted() && $formPhoto->isValid()) {

            $photoData = $formPhoto->getData();

            $photoDataToSend = json_encode([
                'legende_photo' => $photoData->getLegendePhoto(),
                'id_user' => '8',
                'id_event' => $id_event
            ]);

            //foutre id_user de session
            $file = $req->files->get("photo_form")["file_photo"];

            $type = 'photo';

            $rctrl->ajouterPhoto($photoDataToSend, $file, $type, $crl);
        }
        return $formPhoto;
    }

    function createFormCommentaire($id_photo, $req, $rctrl, $crl)
    {
        $commentaire = new Commentaire();

        $formComm = $this->createForm(CommentaireFormType::class, $commentaire);

        $formComm->handleRequest($req);

        if ($formComm->isSubmitted() && $formComm->isValid()) {

            $commentaireData = $formComm->getData();

            $id_user = "";

            $CommentaireDataToSend = json_encode([
                'texte_commentaire' => $commentaireData->getTexteCommentaire(),
                'id_user' => $id_user,
                'id_photo' => $id_photo
            ]);

            //foutre id_user de session
            $rctrl->ajouterCommentaire($CommentaireDataToSend, $crl);
        }
        return $formComm;
    }
}
?>
