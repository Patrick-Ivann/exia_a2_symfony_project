<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\services\Curl;

class RequeteController extends AbstractController
{

    /**
     *
     * TODO penser à rajouter le parametre de token
     */
    public function ajouterEvenement($data, Curl $crl)
    {
        $response = $crl->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/evenement/ajouter", "application/javascript", $data);

        echo $response;
    }


    public function recupererEvenement($data = null)
    {
        $data = $data ? $data : "";

        $query = $this->get("curl")->faireRequeteAvecHeader("GET", "http://10.131.129.13:5000/api/evenement/recuperer", "application/javascript", $data);
    }

    public function ajouterIdee($data)
    {
        $response = $this->get("curl")->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/idee/ajouter", "application/javascript", $data);
    }

    public function recupererIdee($data)
    {
        $data = $data ? $data : "";
        $query = $this->get("curl")->faireRequeteAvecHeader("GET", "http://10.131.129.13:5000/api/idee/recuperer", "application/javascript", $data);
    }

    public function ajouterUtilisateur($data)
    {
        $response = $this->get("curl")->faireRequeteAvecHeader("POST", "http://10.131.129.13:5000/api/utilisateur/ajouter", "application/javascript", $data);
    }
}


