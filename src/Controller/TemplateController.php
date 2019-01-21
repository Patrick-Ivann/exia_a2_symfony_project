<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TemplateController extends AbstractController
{

    /**
     * @Route("/template")
     * @return Response
     */
    public function index(): Response {
        try {
            return $this->render('template.html.twig');
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

}