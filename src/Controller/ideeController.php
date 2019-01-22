<?php

namespace App\Controller;

use App\Entity\Idee;
use App\Form\IdeeFormType;
use App\services\Curl;
use App\Controller\RequeteController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ideeController extends AbstractController
{

    /**
     * @Route("/ideeAdd")
     * @return Response
     */
    public function add(Request $req, RequeteController $rctrl, Curl $crl)
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

            $rctrl->ajouterIdee($ideeDataToSend, $crl);
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

    /**
     * @Route("/ideeGet")
     *
     */
    public function display(RequeteController $rctrl, Curl $crl)
    {

        $idees = $rctrl->recupererIdee($crl);

        //$idees ='{"nom_idee": "Barbecue","nom_lieu" : "Nice"}';
        //variable de test

        $ideesToDisplay = json_decode($idees);

        if(is_object($ideesToDisplay))
        {
            $idees = '[' . $idees . ']';
            $ideesToDisplay = json_decode($idees);
        }


        try {
            return $this->render('ideeDisplay.html.twig', [
                'idees' => $ideesToDisplay
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }

    }
}
?>