<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\PhotoFormType;
use App\services\Curl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class photoController
 * @package App\Controller
 */
class photoController extends AbstractController
{

    /**
     * @Route("/photoAdd")
     * @param Request $req
     * @param RequeteController $rctrl
     * @param Curl $crl
     * @param SessionInterface $session
     * @return string|\Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $req, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        // If user is not connected
        if ($session->get("mail") == null) {
            return $this->redirectToRoute("events");
        }

        $photo = new Photo();

        // Create the form
        $form = $this->createForm(PhotoFormType::class, $photo);
        $form->handleRequest($req);

        // On form submission
        if ($form->isSubmitted() && $form->isValid()) {
            $photoData = $form->getData();

            // Define the JSON to send
            $photoDataToSend = json_encode([
                'legende_photo' => $photoData->getLegendePhoto(),
                'id_user' => '8',
                'id_event' => '9'
            ]);

            $file = $req->files->get("photo_form")["file_photo"];
            $type = 'photo';

            // Add the new photo to the database
            $rctrl->ajouterPhoto($photoDataToSend, $file, $type, $crl);

            // Redirect to events page
            return $this->redirectToRoute("events");
        }

        // Render the page
        try {
            return $this->render('photoCreate.html.twig', [
                'form' => $form->createView()
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}