<?php

namespace App\Controller;

use App\services\Curl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class userController
 * @package App\Controller
 */
class userController extends AbstractController
{

    /**
     * TODO: Commenter entièrement la classe
     */

    /**
     * Allow users to create their account.
     * @Route("/register")
     * @param Request $req
     * @param RequeteController $requeteController
     * @param Curl $crl
     * @return string|\Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $req, RequeteController $requeteController, Curl $crl) {
        $user = New User();

        $errors = "";

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();

            if($userData->getMotdepasse() === $userData->getMotDePasseVerif()) {
                $userDataToSend = json_encode([
                    'prenom' => $userData->getPrenom(),
                    'nom' => $userData->getNom(),
                    'adresse_mail' => $userData->getAdresseMail(),
                    'mot_de_passe' => password_hash($userData->getMotDePasse(), PASSWORD_DEFAULT),
                    'lieu' => $userData->getNomLieu()
                ]);

                $requeteController->ajouterUtilisateur($userDataToSend, $crl);

                return $this->redirect("/exia_a2_symfony_project/public/login");
            } else {
                $errors = "Les mots de passe renseignés ne correspondent pas.";
            }

        }

        try {
            return $this->render('CreateUser.html.twig',[
                'form' =>$form->createView(),
                'erreur' => $errors
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Allow users to log in.
     * @Route("/login")
     * @param Request $req
     * @param RequeteController $requeteController
     * @param Curl $crl
     * @param SessionInterface $session
     * @return string|\Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $req, RequeteController $requeteController, Curl $crl, SessionInterface $session) {

        if ($session->get("mail") != null) {
            return $this->redirect("/exia_a2_symfony_project/public/home");
        }

        $user = New User();

        $errors = "";

        $form = $this->createFormBuilder($user)
            ->add('adresse_mail', TextType::class)
            ->add('mot_de_passe', PasswordType::class)
            ->add('login', SubmitType::class, ['label' => 'Connexion'])
            ->getForm();

        dump($form);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();

            $userDataToSend = json_encode([
                'adresse_mail' => $userData->getAdresseMail()
            ]);

            $response = json_decode($requeteController->connexionUtilisateur($userDataToSend, $crl));

            if (password_verify($user->getMotDePasse(), $response[0]->mot_de_passe)) {

                $session->set("mail", $response[0]->adresse_mail);
                $session->set("prenom", $response[0]->prenom);
                $session->set("nom", $response[0]->nom);
                $session->set("rang", $response[0]->rang);
                $session->set("url_avatar", $response[0]->url_avatar);
                $session->set("id_user", $response[0]->id_user);

                return $this->redirect("/exia_a2_symfony_project/public/home");
            } else {
                $errors = "Le mot de passe renseigné est incorrect.";
            }
        }

        try {
            return $this->render('login.html.twig', [
                'form' =>$form->createView(),
                'erreur' => $errors
            ]);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }



    /**
     * Allow users to log out
     * @Route("/logout")
     * @param Request $req
     * @param RequeteController $requeteController
     * @param Curl $crl
     * @param SessionInterface $session
     * @return string|\Symfony\Component\HttpFoundation\Response
     */
    public function logout(Request $req, RequeteController $requeteController, Curl $crl, SessionInterface $session) {

        if ($session->get("mail") == null) {
            return $this->redirect("/exia_a2_symfony_project/public/home");
        }

        $session->remove("mail");
        $session->remove("rang");
        $session->remove("prenom");
        $session->remove("nom");
        $session->remove("url_avatar");
        $session->remove("id_user");

        return $this->redirect("/exia_a2_symfony_project/public/home");
    }

}
