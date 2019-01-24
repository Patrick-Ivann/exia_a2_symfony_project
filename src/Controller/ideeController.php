<?php

namespace App\Controller;

use App\Entity\Idee;
use App\Form\IdeeFormType;
use App\services\Curl;
use App\Controller\RequeteController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ideeController extends AbstractController
{

    /**
     * @Route("/ideeAdd")
     */
    public function add(Request $req, RequeteController $rctrl, Curl $crl)
    {

        $idee = new Idee();

        $form = $this->createForm(IdeeFormType::class, $idee);

        $form->handleRequest($req);


        if ($form->isSubmitted() && $form->isValid()) {
            $ideeData = $form->getData();

            $ideeDataToSend = json_encode([
                'nom_idee' => $ideeData->getNomIdee(),
                'description_idee' => $ideeData->getDescriptionIdee(),
                //Doit envoyer l'id user evoyer par la session
                'lieu' => $ideeData->getLieu()]);


           $rctrl->ajouterIdee($ideeDataToSend, $crl);
        }

        {
            try {
                return $this->render('ideeCreate.html.twig',[
                    'form' => $form->createView()
                ]);
            } catch (\Exception $ex) {
                return $ex->getMessage();
            }
        }
    }

    /**
     * @Route("/idees", name="idees")
     *
     */
    public function display(RequeteController $rctrl, Curl $crl)
    {

        $idees = $rctrl->recupererIdee($crl);

        $ideesToDisplay = json_decode($idees);

        if(is_object($ideesToDisplay))
        {
            $idees = '[' . $idees . ']';
            $ideesToDisplay = json_decode($idees);
        }
        dump($ideesToDisplay);
        try {
            return $this->render('ideeDisplay.html.twig', [
                'idees' => $ideesToDisplay
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }

    }


    /**
     * @Route("/vote/{id_event_idee}" , name="voteById")
     */
    function vote($id_event_idee, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        $id_user = $session->get("id_user");

        $like = json_encode([
            'id_event_idee' => $id_event_idee,
            'id_user' => $id_user
        ]);

        $rctrl->publierUnLikeSurEventIdee($like, $crl);

        return $this->redirectToRoute("idees");
    }


}
?>