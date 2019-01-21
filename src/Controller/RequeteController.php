<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\services\Curl;

class RequeteController extends AbstractController
{

    /**
     * TODO penser à rajouter le parametre de token
     */
    public function ajouterEvenement($data)
    {
        $response = $this->get("curl")->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/evenement/ajouter", "application/javascript", $data);

    }

}


