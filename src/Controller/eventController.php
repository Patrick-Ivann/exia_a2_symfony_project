<?php
namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Event;
use App\Entity\Photo;
use App\Form\CommentaireFormType;
use App\Form\EventFormType;
use App\Form\PhotoFormType;
use App\services\Curl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
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
        return $this->redirectToRoute("events");
    }

    /**
     * @Route("/event/{id_event}" , name="eventById")
     * @param \App\Controller\RequeteController $rctrl
     * @param Curl $crl
     * @return string|Response
     */
    public function displayById($id_event, Request $req, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        $events = $rctrl->recupererEvenementParId($id_event, $crl);

        $eventToDisplay = json_decode($events);

        $photo = $rctrl->recupererPhotoParIdEvent($id_event, $crl);

        $photoToDisplay = json_decode($photo);

        foreach ($photoToDisplay as $photos) {

            $commentaire[] = $rctrl->recupererCommentaireParIdPhoto($photos->{"id_photo"}, $crl);

            $formComm[] = $this->createFormCommentaire($photos->id_photo, $req, $rctrl, $crl, $session);
        }
/*
        foreach ($commentaire as $commentaire)
        {
            $commentaireToDisplay[] = json_decode($commentaire);
        }
        dump($commentaireToDisplay); */

        foreach ($formComm as $form) {
            $formCommCreated[] = $form->createView();

        }

        dump($formCommCreated);
        dump($commentaire);
        /*
            Faire le traitement pour choper l'image et son nom
         */

        //Form en cas d'ajout photo
        $formPhoto = $this->createFormPhoto($id_event, $req, $rctrl, $crl, $session);

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

    function createFormPhoto($id_event, $req, $rctrl, $crl, SessionInterface $session)
    {
        $photo = new Photo();

        $formPhoto = $this->createForm(PhotoFormType::class, $photo);

        $formPhoto->handleRequest($req);

        if ($formPhoto->isSubmitted() && $formPhoto->isValid()) {
            $photoData = $formPhoto->getData();


            $id_user = $session->get("id_user");

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

    function createFormCommentaire($id_photo, $req, $rctrl, $crl, SessionInterface $session)
    {
        $commentaire = new Commentaire();

        $formComm = $this->createForm(CommentaireFormType::class, $commentaire);

        $formComm->handleRequest($req);

        if ($formComm->isSubmitted() && $formComm->isValid()) {

            $commentaireData = $formComm->getData();

            $id_user = $session->get("id_user");

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

    /**
     * @Route("/download/{id_event}" , name="downloadPDF")
     */
    function telecharge_en_pdf($id_event, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        /*$id_user = $session->get("id_user");

        $participe = json_encode([
            'id_event' => $id_event,
            'id_user' => $id_user
        ]);

        $rctrl->participerUnEvenementParId($participe, $crl);*/

        //return $this->redirectToRoute("events");

        $participants = json_decode($rctrl->recupererEvenementParticipe($id_event, $crl));

        $pdf = new \FPDF();

        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        $pdf->Text(80, 20, "Liste des inscrits");

        $gap = 0;

        foreach ($participants as $participant) {
            $pdf->Text(40, 40+$gap, "Nom : ");
            $pdf->Text(100, 50+$gap, $participant->nom);
            $pdf->Text(40, 60+$gap, "Prenom : ");
            $pdf->Text(100, 40+$gap, $participant->prenom);
            $pdf->Text(40, 50+$gap, "Adresse mail : ");
            $pdf->Text(100, 60+$gap, $participant->adresse_mail);
            $gap += 50;
        }

        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));
    }

    /**
     * @Route("/downloadcsv/{id_event}" , name="downloadCSV")
     */
    function telecharge_en_csv($id_event, RequeteController $rctrl, Curl $crl)
    {
        $participants = json_decode($rctrl->recupererEvenementParticipe($id_event, $crl));

    }
}
