<?php

namespace App\Controller;

use App\services\Curl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class JuridicalController
 * @package App\Controller
 */
class JuridicalController extends AbstractController
{

    /**
     * @Route("/mentions")
     * @param RequeteController $rctrl
     * @param Curl $crl
     * @param SessionInterface $session
     * @return Response
     */
    public function mentions(RequeteController $rctrl, Curl $crl, SessionInterface $session): Response
    {
        // Check for new notifications
        $notifs = null;
        if ($session->get("mail") != null) {
            $notifs = json_decode($rctrl->recupererUtilisateurNotif($session->get("id_user"), $crl));
        }

        // Render the page
        try {
            return $this->render('pages/mentions.html.twig', [
                'notifs' => $notifs
            ]);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }

    /**
     * @Route("/cgv")
     * @param RequeteController $rctrl
     * @param Curl $crl
     * @param SessionInterface $session
     * @return Response
     */
    public function cgv(RequeteController $rctrl, Curl $crl, SessionInterface $session): Response
    {
        // Check for new notifications
        $notifs = null;
        if ($session->get("mail") != null) {
            $notifs = json_decode($rctrl->recupererUtilisateurNotif($session->get("id_user"), $crl));
        }

        // Render the page
        try {
            return $this->render('pages/cgv.html.twig', [
                'notifs' => $notifs
            ]);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }

    /**
     * @Route("/rules")
     * @param RequeteController $rctrl
     * @param Curl $crl
     * @param SessionInterface $session
     * @return Response
     */
    public function rules(RequeteController $rctrl, Curl $crl, SessionInterface $session): Response
    {
        // Check for new notifications
        $notifs = null;
        if ($session->get("mail") != null) {
            $notifs = json_decode($rctrl->recupererUtilisateurNotif($session->get("id_user"), $crl));
        }

        // Render the page
        try {
            return $this->render('pages/rules.html.twig', [
                'notifs' => $notifs
            ]);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }

}