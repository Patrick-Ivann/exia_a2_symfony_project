<?php

namespace App\Controller;

use App\services\Curl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{

    /**
     * @Route("/")
     * @Route("/home", name="home")
     * @param RequeteController $rctrl
     * @param Curl $crl
     * @param SessionInterface $session
     * @return Response
     */
    public function index(RequeteController $rctrl, Curl $crl, SessionInterface $session) : Response
    {
        // Check for new notifications
        $notifs = null;
        if ($session->get("mail") != null) {
            $notifs = json_decode($rctrl->recupererUtilisateurNotif($session->get("id_user"), $crl));
        }

        // Get the most sell items
        $products = json_decode($rctrl->recupererProduitLesPlusVendus($crl));

        // Render the page
        try {
            return $this->render('pages/home.html.twig', [
                'notifs' => $notifs,
                'products' => $products
            ]);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }

}