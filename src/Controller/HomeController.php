<?php

namespace App\Controller;

use App\services\Curl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/")
     * @Route("/home", name="home")
     * @return Response
     */
    public function index(RequeteController $rctrl, Curl $crl, SessionInterface $session) : Response
    {
        $notifs = null;
        if ($session->get("mail") != null) {
            $notifs = json_decode($rctrl->recupererUtilisateurNotif($session->get("id_user"), $crl));
        }

        try {
            return $this->render('pages/home.html.twig', [
                'notifs' => $notifs
            ]);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }

}