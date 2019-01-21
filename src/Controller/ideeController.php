<?php

namespace App\Controller;

use App\Entity\Idee;
use App\Form\IdeeFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ideeController extends AbstractController
{

    /**
     * @Route("/ideeController")
     * @return Response
     */
    public function index(Request $req)
    {

        $idee = new Idee();

        $form = $this->createForm(IdeeFormType::class, $idee);

        $form->handleRequest($req);


        if ($form->isSubmitted() && $form->isValid()) {
            $ideeData = $form->getData();

            $ideeDataToSend = json_encode(['nom_idee' => $ideeData->getNomIdee(),
                'description_idee' => $ideeData->getDescriptionIdee(),
                //Doit envoyer l'id user aussi
                'nom_lieu' => $ideeData->getNomLieu()]);

            dump($ideeDataToSend);
        }


        {
            try {
                return $this->render('ideeCreate.html.twig',[
                    'form' =>$form->createView()
                ]);
            } catch (\Exception $ex) {
                return $ex->getMessage();
            }
        }
    }
}
?>