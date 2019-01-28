<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitFormType;
use App\services\Curl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class boutiqueController extends AbstractController
{

    /**
     * @Route("/product/add")
     */
    function add(Request $req, RequeteController $rctrl, Curl $crl)
    {
        $produit = new Produit();

        $form = $this->createForm(ProduitFormType::class, $produit);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitData = $form->getData();

            $produitDataToSend = json_encode([
                'nom_produit' => $produitData->getNomProduit(),
                'prix_produit' => $produitData->getPrixProduit(),
              //  'extension' => $req->files->get("produit_form")["avatar"]->guessExtension()
            ]);

            dump($req->files);
            $file = $req->files->get("produit_form")["photo_produit"];

            $type = "produit";

            $rctrl->ajouterProduit($produitDataToSend, $file, $type, $crl);
        }

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
     */
    function delete($id_produit, Request $req, RequeteController $rctrl, Curl $crl)
    {
        $rctrl->supprimerProduit($id_produit, $crl);

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
     * @return Response
     */
    function display(RequeteController $rctrl, Curl $crl)
    {
        $produits = $rctrl->recupererTousLesProduits($crl);

        $produitsToDisplay = json_decode($produits);

        if (is_object($produitsToDisplay)) {
            $produits = '[' . $produits . ']';
            $produitsToDisplay = json_decode($produits);
        }

        dump($produitsToDisplay);

        try {
            return $this->render('produitDisplay.html.twig', [
                'produits' => $produitsToDisplay
            ]);
        } catch (\Exception $ex) {
            return new Response($ex->getMessage());
        }
    }

    /**
     * @Route("/buy/{id_produit}" , name="buyById")
     */
    function buy($id_produit, RequeteController $rctrl, Curl $crl, SessionInterface $session)
    {
        $id_user = $session->get("id_user");

        $achat = json_encode([
            'id_produit' => $id_produit,
            'id_user' => $id_user
        ]);

        $rctrl->acheter($achat, $crl);

        return $this->redirectToRoute("boutique");
    }
}
?>
