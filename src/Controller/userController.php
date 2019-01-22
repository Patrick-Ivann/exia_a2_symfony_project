<?php

namespace App\Controller;

use App\services\Curl;
use App\Controller\RequeteController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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

      $form = $this->createForm(UserFormType::class, $user);

      $form->handleRequest($req);

      if ($form->isSubmitted() && $form->isValid()) {
        $userData = $form->getData();

        $userDataToSend = json_encode([
            'prenom'=>$userData->getPrenom(),
            'nom'=>$userData->getNom(),
            'mail'=>$userData->getMail(),
            'mdp'=>$userData->getMdp(),
            'url_avatar'=>$userData->getUrlAvatar(),
            'lieu'=>$userData->getLieu()
        ]);

        $rctrl->ajouterUtilisateur($userDataToSend);
      }

        try {
          return $this->render('CreateUser.html.twig',[
                  'form' =>$form->createView()
              ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

}
