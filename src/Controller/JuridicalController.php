<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JuridicalController extends AbstractController
{

    /**
     * @Route("/mentions")
     * @return Response
     */
    public function mentions(): Response {
        try {
            return $this->render('pages/mentions.html.twig');
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }

    /**
     * @Route("/cgv")
     * @return Response
     */
    public function cgv(): Response {
        try {
            return $this->render('pages/cgv.html.twig');
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }

    /**
     * @Route("/personaldata")
     * @return Response
     */
    public function personalData(): Response {
        try {
            return $this->render('pages/personal_data.html.twig');
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }

}