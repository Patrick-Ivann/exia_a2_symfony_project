<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Form\ProduitFormType;
use App\services\Curl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class boutiqueController
 * @package App\Controller
 */
class boutiqueController extends AbstractController
{

    /**
     * @Route("/product/add")
     * @param Request $req
     * @param RequeteController $rctrl
     * @param Curl $crl
     * @param SessionInterface $session
     * @return string|Response
     */
    function add(Request $req, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        // If user is not connected
        if ($session->get("mail") == null) {
            return $this->redirectToRoute("products");
        }

        $produit = new Produit();

        // Create form
        $form = $this->createForm(ProduitFormType::class, $produit);
        $form->handleRequest($req);

        // On form submission
        if ($form->isSubmitted() && $form->isValid()) {
            $produitData = $form->getData();

            $produitDataToSend = json_encode([
                'nom_produit' => $produitData->getNomProduit(),
                'prix_produit' => $produitData->getPrixProduit(),
            ]);

            dump($req->files);
            $file = $req->files->get("produit_form")["photo_produit"];

            $type = "produit";

            // Add product to the database
            $rctrl->ajouterProduit($produitDataToSend, $file, $type, $crl);

            // Redirect to products page
            return $this->redirectToRoute("products");
        }

        // Render the page
        try {
            return $this->render('produitCreate.html.twig', [
                'form' => $form->createView()
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * @Route("/product/delete/{id_produit}", name="supprimer_produit")
     * @param $id_produit
     * @param Request $req
     * @param RequeteController $rctrl
     * @param Curl $crl
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    function delete($id_produit, Request $req, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        // If user is not connected
        if ($session->get("mail") == null) {
            return $this->redirectToRoute("products");
        }

        // Delete the product
        $rctrl->supprimerProduit($id_produit, $crl);

        // Redirect to products page
        try {
            return $this->redirectToRoute("products");
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }

    /**
     * @Route("/boutique", name="products")
     * @param RequeteController $rctrl
     * @param Curl $crl
     * @param Request $req
     * @param SessionInterface $session
     * @return Response
     */
    function display(RequeteController $rctrl,Curl $crl, Request $req, SessionInterface $session)
    {
        $id_user = $session->get("id_user");

        $produits = $rctrl->recupererTousLesProduits($crl);

        $produitsToDisplay = json_decode($produits);

        if (is_object($produitsToDisplay)) {
            $produits = '[' . $produits . ']';
            $produitsToDisplay = json_decode($produits);
        }

        dump($produitsToDisplay);

        $formPanier = $this->panier($rctrl, $req,$produitsToDisplay, $crl, $id_user);

        // Render the page
        try {
            return $this->render('produitDisplay.html.twig', [
                'produits' => $produitsToDisplay,
                'form' => $formPanier->createView()
            ]);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }

    /**
     * @param $rctrl
     * @param Request $req
     * @param $produitToDisplay
     * @param $crl
     * @param $id_user
     * @return \Symfony\Component\Form\FormInterface
     */
    public function panier($rctrl ,Request $req, $produitToDisplay, $crl, $id_user){
        $panier = new Panier();
        $form = $this->createFormBuilder($panier);

        if($produitToDisplay){
            foreach ($produitToDisplay as $key=>$produit){
                $nom_article = 'article'.$produit->id_produit;
                $panier->$nom_article = null;

                $form->add('article'.$produit->id_produit, CheckboxType::class, [
                    'label'    => 'Acheter',
                    'required' => false,
                ]);
            }
        }

        // Create form
        $formFinal = $form->getForm();
        $formFinal->handleRequest($req);

        // On form submission
        if ($formFinal->isSubmitted() && $formFinal->isValid()) {
            $dataPanier = $formFinal->getData();

            foreach ($produitToDisplay as $key=>$produit) {
                $nom_article = 'article'.$produit->id_produit;
                $dataPanier->$nom_article;

                if ($dataPanier->$nom_article) {
                    $panierDataToSend = json_encode([
                        'id_user' => $id_user,
                        'id_produit' => $produit->id_produit
                    ]);
                    $rctrl->acheter($panierDataToSend, $crl);
                }
            }
        }

        return $formFinal;
    }

    /**
     * @Route("/buy/{id_produit}" , name="buyById")
     * @param $id_produit
     * @param RequeteController $rctrl
     * @param Curl $crl
     * @param SessionInterface $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    function buy($id_produit, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        // If user is not connected
        if ($session->get("mail") == null) {
            return $this->redirectToRoute("boutique");
        }

        // Get user session id
        $id_user = $session->get("id_user");

        $achat = json_encode([
            'id_produit' => $id_produit,
            'id_user' => $id_user
        ]);

        // Add entry in buys table
        $rctrl->acheter($achat, $crl);

        // Redirect to shop page
        return $this->redirectToRoute("boutique");
    }
}
