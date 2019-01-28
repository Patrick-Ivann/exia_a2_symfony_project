<?php

namespace App\Controller;

use App\services\Curl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class JuridicalController extends AbstractController
{

    /**
     * @Route("/mentions")
     * @return Response
     */
    public function mentions(RequeteController $rctrl, Curl $crl, SessionInterface $session): Response
    {
        $notifs = null;
        if ($session->get("mail") != null) {
            $notifs = json_decode($rctrl->recupererUtilisateurNotif($session->get("id_user"), $crl));
        }

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
     * @return Response
     */
    public function cgv(RequeteController $rctrl, Curl $crl, SessionInterface $session): Response
    {
        $notifs = null;
        if ($session->get("mail") != null) {
            $notifs = json_decode($rctrl->recupererUtilisateurNotif($session->get("id_user"), $crl));
        }

        try {
            return $this->render('pages/cgv.html.twig', [
                'notifs' => $notifs
            ]);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }

    /**
     * @Route("/personaldata")
     * @return Response
     */
    public function personalData(RequeteController $rctrl, Curl $crl, SessionInterface $session): Response
    {
        $notifs = null;
        if ($session->get("mail") != null) {
            $notifs = json_decode($rctrl->recupererUtilisateurNotif($session->get("id_user"), $crl));
        }

        try {
            return $this->render('pages/personal_data.html.twig', [
                'notifs' => $notifs
            ]);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }

}