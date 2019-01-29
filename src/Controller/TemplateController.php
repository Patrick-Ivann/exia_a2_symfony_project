<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TemplateController
 * @package App\Controller
 */
class TemplateController extends AbstractController
{

    /**
     * Template function with rendering method
     * @Route("/template")
     * @return Response
     */
    public function index(): Response {
        try {
            return $this->render('template.html.twig');
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }

}