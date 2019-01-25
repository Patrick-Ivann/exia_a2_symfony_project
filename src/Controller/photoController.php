<?php

namespace App\Controller;


use App\Entity\Photo;
use App\Form\PhotoFormType;
use App\Services\Curl;
use App\Controller\RequeteController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class photoController extends AbstractController
{
    /**
     * @Route("/photoAdd")
     */
    public function add(Request $req, RequeteController $rctrl, Curl $crl)
    {
        $photo = new Photo();

        $form = $this->createForm(PhotoFormType::class, $photo);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoData = $form->getData();

            $photoDataToSend = json_encode([
                'legende_photo' => $photoData->getLegendePhoto(),
                'id_user' => '8',
                'id_event' => '9'
            ]);

            $file = $req->files->get("photo_form")["file_photo"];

            $type = 'photo';


            $rctrl->ajouterPhoto($photoDataToSend, $file, $type, $crl);

        }

        try {
            return $this->render('photoCreate.html.twig', [
                'form' => $form->createView()
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }


}

?>