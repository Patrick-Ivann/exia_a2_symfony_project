<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Produit;
use App\Form\EventFormType;
use App\Controller\RequeteController;
use App\services\Curl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class eventController extends AbstractController
{

    /**
     * @Route("/eventAdd")
     *
     */
    public function add(Request $req, RequeteController $rctrl, Curl $crl)
    {

        $event = new Event();

        $form = $this->createForm(EventFormType::class,$event);

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            $eventData = $form->getData();

            $eventDataToSend = json_encode([
                            'nom_event' => $eventData->getNomEvent(),
                            'date_debut_event' => $eventData->getDateDebutEvent(),
                            'date_fin_event' => $eventData->getDateFinEvent(),
                            'nom_lieu' => $eventData->getNomLieu(),
                            'type_event' => $eventData->getTypeEvent(),
                            'prix' => $eventData->getPrix()]);

            $rctrl->ajouterEvenement($eventDataToSend, $crl);
        }

        try {
            return $this->render('eventCreate.html.twig', [
                'form' =>$form->createView()
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @Route("/eventGet", name="displayEvent")
     */
    public function display(RequeteController $rctrl, Curl $crl)
    {

        $events = $rctrl->recupererEvenement(null,$crl);

        $eventToDisplay = json_decode($events);

        if(is_object($eventToDisplay)){
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
    public function delete($id_event,RequeteController $rctrl, Curl $crl)
    {
        //$rctrl->supprimerEvenement($id_event, $crl);

       return $this->redirectToRoute("displayEvent");
    }

    /**
     * @Route("/event/{id_event}" , name="eventById")
     * @param \App\Controller\RequeteController $rctrl
     * @param Curl $crl
     * @return string|Response
     */
    public function displayById($id_event, RequeteController $rctrl, Curl $crl)
    {
        $events = $rctrl->recupererEvenementParId($id_event,$crl);

        $eventToDisplay = json_decode($events);

        //$photo = $rctrl->recupererPhotoParIdEvent();



        try {
            return $this->render('eventDisplayID.html.twig', [
                'event' => $eventToDisplay
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}