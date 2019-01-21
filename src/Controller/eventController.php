<?php

namespace App\Controller;

use App\Entity\Event;
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
     * @Route("/eventController")
     * @return Response
     */
    public function add(Request $req, RequeteController $rctrl, Curl $crl): Response {

        $event = new Event();

        $form = $this->createForm(EventFormType::class,$event);

        $form->handleRequest($req);


        if($form->isSubmitted() && $form->isValid()){
            $eventData = $form->getData();

            $eventDataToSend = json_encode(['nom_event' => $eventData->getNomEvent(),
                            'date_debut_event' => $eventData->getDateDebutEvent(),
                            'date_fin_event' => $eventData->getDateFinEvent(),
                            'nom_lieu' => $eventData->getNomLieu()]);

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

}