<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/")
     * @Route("/home")
     * @return Response
     */
    public function index(): Response {
        try {
            return $this->render('pages/home.html.twig');
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }

}