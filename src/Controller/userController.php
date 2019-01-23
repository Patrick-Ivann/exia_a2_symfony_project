<?php

namespace App\Controller;

use App\services\Curl;
use App\Controller\RequeteController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Component\HttpFoundation\Request;

class userController extends AbstractController
{

    /**
     * @Route("/signup")
     */
    public function add(Request $req, RequeteController $rctrl, Curl $crl) {
      $user = New User();

      $err = "";

      $form = $this->createForm(UserFormType::class, $user);

      $form->handleRequest($req);

      if ($form->isSubmitted() && $form->isValid()) {
        $userData = $form->getData();

        if($userData->getMotdepasse() === $userData->getMotDePasseVerif()) {
            $userDataToSend = json_encode([
                'prenom' => $userData->getPrenom(),
                'nom' => $userData->getNom(),
                'adresse_mail' => $userData->getAdresseMail(),
                'mot_de_passe' => $userData->getMotDePasse(),
                'nom_lieu' => $userData->getNomLieu()
            ]);

            dump($userDataToSend);
           // $rctrl->ajouterUtilisateur($userDataToSend, $crl);
        }
        else{
            $err = "Les mot de passe sont différents";
        }

      }

        try {
          return $this->render('CreateUser.html.twig',[
              'form' =>$form->createView(),
              'erreur' => $err
              ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

}
