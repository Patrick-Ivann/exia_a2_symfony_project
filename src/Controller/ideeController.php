<?php

namespace App\Controller;

use App\Entity\Idee;
use App\Form\IdeeFormType;
use App\services\Curl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ideeController
 * @package App\Controller
 */
class ideeController extends AbstractController
{

    /**
     * @Route("/ideas/add", name="ideeAdd")
     * @param Request $req
     * @param RequeteController $rctrl
     * @param Curl $crl
     * @param SessionInterface $session
     * @return string|Response
     */
    public function add(Request $req, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        // Check for new notifications
        $notifs = null;
        if ($session->get("mail") != null) {
            $notifs = json_decode($rctrl->recupererUtilisateurNotif($session->get("id_user"), $crl));
        } else {
            // If user is not connected, redirect to ideas page
            return $this->redirectToRoute("idees");
        }

        $idee = new Idee();

        // Create form
        $form = $this->createForm(IdeeFormType::class, $idee);
        $form->handleRequest($req);

        // On form submission
        if ($form->isSubmitted() && $form->isValid()) {
            $ideeData = $form->getData();

            $ideeDataToSend = json_encode([
                'nom_idee' => $ideeData->getNomIdee(),
                'description_idee' => $ideeData->getDescriptionIdee(),
                'id_user' => $session->get("id_user"),
                'lieu' => $ideeData->getLieu()
            ]);

            // Add the new idea to the database
            $rctrl->ajouterIdee($ideeDataToSend, $crl);

            // Redirect to ideas page
            return $this->redirectToRoute("idees");
        }

        // Render the page
        try {
            return $this->render('ideeCreate.html.twig', [
                'form' => $form->createView(),
                'notifs' => $notifs
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @Route("/ideas", name="idees")
     * @param RequeteController $rctrl
     * @param Curl $crl
     * @param SessionInterface $session
     * @return Response
     */
    public function display(RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        // Check for new notifications
        $notifs = null;
        if ($session->get("mail") != null) {
            $notifs = json_decode($rctrl->recupererUtilisateurNotif($session->get("id_user"), $crl));
        }

        // Get the saved ideas
        $idees = $rctrl->recupererIdee($crl);
        $ideesToDisplay = json_decode($idees);

        if (is_object($ideesToDisplay)) {
            $idees = '[' . $idees . ']';
            $ideesToDisplay = json_decode($idees);
        }

        // Get the users who liked
        $users = array();
        foreach ($ideesToDisplay as $idea) {
            $users[$idea->id_user] = json_decode($rctrl->recupererUtilisateurParId($idea->id_user, $crl));
        }

        // Get the likes
        $likes = array();
        foreach ($ideesToDisplay as $idea) {
            $likes[$idea->id_event_idee] = json_decode($rctrl->recupererEventIdeeAime($idea->id_event_idee, $crl));
        }

        // Render the page
        try {
            return $this->render('ideeDisplay.html.twig', [
                'idees' => $ideesToDisplay,
                'users' => $users,
                'likes' => $likes,
                'notifs' => $notifs
            ]);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage() );
        }

    }


    /**
     * @Route("/vote/{id_event_idee}" , name="voteById")
     * @param $id_event_idee
     * @param RequeteController $rctrl
     * @param Curl $crl
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    function vote($id_event_idee, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        // If user is not connected
        if ($session->get("mail") == null) {
            return $this->redirectToRoute("idees");
        }

        // Get session user id
        $id_user = $session->get("id_user");

        $like = json_encode([
            'id_event_idee' => $id_event_idee,
            'id_user' => $id_user
        ]);

        // Add the like to the idea
        $rctrl->publierUnLikeSurEventIdee($like, $crl);

        // Redirect to ideas page
        return $this->redirectToRoute("idees");
    }


}