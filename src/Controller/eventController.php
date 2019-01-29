<?php
namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Event;
use App\Entity\Photo;
use App\Form\CommentaireFormType;
use App\Form\EventFormType;
use App\Form\PhotoFormType;
use App\services\Curl;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

class eventController extends AbstractController
{
    /**
     * @Route("/events/add", name="event_add")
     * @param Request $req
     * @param \App\Controller\RequeteController $rctrl
     * @param Curl $crl
     * @return string|Response
     */
    public function add(Request $req, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        $notifs = null;
        if ($session->get("mail") != null) {
            $notifs = json_decode($rctrl->recupererUtilisateurNotif($session->get("id_user"), $crl));
        }

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

            if ($req->get("id_user") != null) {
                $data = json_encode([
                    'id_event_idee' => $req->get("id_event_idee"),
                    'id_user' => $req->get("id_user")
                ]);

                $rctrl->publierUnUtilisateurANotifie($data, $crl);
            }

            $this->redirectToRoute("events");
        }

        try {
            return $this->render('eventCreate.html.twig', [
                'form' => $form->createView(),
                'post' => $req->get("name"),
                'notifs' => $notifs
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
    public function display(RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        $notifs = null;
        if ($session->get("mail") != null) {
            $notifs = json_decode($rctrl->recupererUtilisateurNotif($session->get("id_user"), $crl));
        }

        $events = $rctrl->recupererEvenement(null, $crl);
        $eventToDisplay = json_decode($events);

        $participants = array();
        $participantsToDisplay = array();

        if (is_array($eventToDisplay)) {
            dump("ok");
            foreach ($eventToDisplay as $event) {
                $participants[$event->id_event] = json_decode($rctrl->recupererEvenementParticipe($event->id_event, $crl));
            }
        } else {
            $participants[$eventToDisplay->id_event] = json_decode($rctrl->recupererEvenementParticipe($eventToDisplay->id_event, $crl));
        }

        foreach ($participants as $key => $participant) {
            foreach ($participant as $user) {
                if ($session->get("id_user") == $user->id_user) {
                    $participantsToDisplay[$key] = $user;
                }
            }
        }

        dump($participantsToDisplay);

        if (is_object($eventToDisplay)) {
            $events = '[' . $events . ']';
            $eventToDisplay = json_decode($events);
        }

        try {
            return $this->render('eventDisplay.html.twig', [
                'events' => $eventToDisplay,
                'participantsList' => $participants,
                'participants' => $participantsToDisplay,
                'notifs' => $notifs
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @Route("/deleteShop/{id_event}" , name="deleteEvent")
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
        $notifs = null;
        if ($session->get("mail") != null) {
            $notifs = json_decode($rctrl->recupererUtilisateurNotif($session->get("id_user"), $crl));
        }

        $id_user = $session->get("id_user");

        $events = $rctrl->recupererEvenementParId($id_event, $crl);

        $eventToDisplay = json_decode($events);

        $photo = $rctrl->recupererPhotoParIdEvent($id_event, $crl);

        $photoToDisplay = json_decode($photo);
        $finalPhotos = json_decode($photo);

        $commentaire = [];
        $likes = array();

        dump($photoToDisplay);

        if(is_array($photoToDisplay)) {
            foreach ($photoToDisplay as $key => $photo) {
                //$commentaire[] = $rctrl->recupererCommentaireParIdPhoto($photo->{"id_photo"}, $crl);
                $likes[$photo->id_photo] = json_decode($rctrl->recupererPhotoAimee($photo->id_photo, $crl));
            }
        } else {
            $finalPhotos = array();
            $finalPhotos[0] = $photoToDisplay;

            $likes[$photoToDisplay->id_photo] = json_decode($rctrl->recupererPhotoAimee($photoToDisplay->id_photo, $crl));
        }

        /*
            Faire le traitement pour choper l'image et son nom
         */

        //Form en cas d'ajout photo
        $formPhoto = $this->createFormPhoto($id_event, $req, $rctrl, $crl, $id_user);

        $participants = json_decode($rctrl->recupererEvenementParticipe($id_event, $crl));

        $participantsToDisplay = array();
        foreach ($participants as $key => $participant) {
            if ($participant->id_user == $session->get("id_user")) {
                $participantsToDisplay[$key] = $participant;
            }
        }

        $event_passe = false;
        $date = new \DateTime($eventToDisplay->date_debut_event);
        $date = $date->format('d/m/Y');
        $now = date('d/m/Y');

        $date_a = explode('/', $date);
        $now_a = explode('/', $now);

        if ((int)$date_a[2] <= (int)$now_a[2]) {
            if ((int)$date_a[1] <= (int)$now_a[1]) {
                if ((int)$date_a[0] <= (int)$now_a[0]) {
                    $event_passe = true;
                }
            }
        }

        try {
            return $this->render('eventDisplayID.html.twig', [
                'event' => $eventToDisplay,
                'photo' => $finalPhotos,
                'commentaire' => $commentaire,
                'formPhoto' => $formPhoto->createView(),
                'notifs' => $notifs,
                'participants' => $participantsToDisplay,
                'likes' => $likes,
                'date' => $event_passe
            ]);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
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
        $notifs = null;
        if ($session->get("mail") != null) {
            $notifs = json_decode($rctrl->recupererUtilisateurNotif($session->get("id_user"), $crl));
        }

        $id_user = $session->get("id_user");

        $photo = $rctrl->recupererPhotoParId($id_photo, $crl);

        $commentaire = $rctrl->recupererCommentaireParIdPhoto($id_photo, $crl);

        $photoToDisplay = json_decode($photo);

        $commentaireToDisplay = json_decode($commentaire);

        $formComm = $this->createFormCommentaire($id_photo, $req, $rctrl, $crl, $id_user);

        $alone = null;
        if(is_array($commentaireToDisplay))
        {
            foreach ($commentaireToDisplay as $com){
                $com->name_user = json_decode($rctrl->recupererUtilisateurParId($com->id_user, $crl))->prenom;
                $alone = false;
            }
        } else if (is_object($commentaireToDisplay)){
            $commentaireToDisplay->name_user = json_decode($rctrl->recupererUtilisateurParId($commentaireToDisplay->id_user, $crl))->prenom;
            $alone = true;
        }

        dump($commentaireToDisplay);

        try {
            return $this->render('photoDisplayID.html.twig', [
                'photo' => $photoToDisplay,
                'commentaire' => $commentaireToDisplay,
                'formComm' => $formComm->createView(),
                'alone' => $alone,
                'notifs' => $notifs
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
    function signale($id_photo, RequeteController $rctrl, Curl $crl, SessionInterface $session, \Swift_Mailer $mailer)
    {
        $id_user = $session->get("id_user");

        $signale = json_encode([
            'id_photo' => $id_photo,
            'id_user' => $id_user
        ]);

        $rctrl->signalerUnePhotoParId($signale, $crl);

        $user = json_decode($rctrl->recupererUtilisateurParId($id_user, $crl));
        dump($user);

        $message = (new \Swift_Message('[BDE] Signalement'))
            ->setFrom('bde-tls@cesi.fr')
            ->setTo('julien.griffoul@viacesi.fr')
            ->setBody("Une image a été signalée");

        $mailer->send($message);

        return $this->redirectToRoute("events");
    }

    /**
     * @Route("/participe/{id_event}" , name="participeById")
     * @param $id_event
     * @param RequeteController $rctrl
     * @param Curl $crl
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
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

        dump($participants);

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
        $formattedParticipants = array();

        foreach ($participants as $key => $participant) {
            $formattedParticipants[$key] = (array) $participant;
        }

        dump($formattedParticipants);

        $encoder = new CsvEncoder();
        $csv = $encoder->encode($formattedParticipants, 'csv');

        $spreadsheet = new Spreadsheet();

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Nom');
        $sheet->setCellValue('B1', 'Prénom');
        $sheet->setCellValue('C1', 'Mail');

        $temp = 1;

        foreach ($formattedParticipants as $key => $user) {
            dump($user);
            $sheet->setCellValue('A'. ($temp), $user["nom"]);
            $sheet->setCellValue('B'. ($temp), $user["prenom"]);
            $sheet->setCellValue('C'. ($temp), $user["adresse_mail"]);
            $temp++;
        }

        // Create the instance
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'export_participants.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
