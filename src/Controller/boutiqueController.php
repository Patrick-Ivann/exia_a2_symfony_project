<?php

namespace App\Controller;

use App\Entity\Idee;
use App\Entity\Produit;
use App\Form\IdeeFormType;
use App\Form\ProduitFormType;
use App\Services\Curl;
use App\Controller\RequeteController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class boutiqueController extends AbstractController
{

    /**
     * @Route("/produitAdd")
     *
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

            $file = $req->files->get("produit_form")["avatar"];



            $rctrl->ajouterProduit($produitDataToSend, $file, $crl);
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
     * @Route("/boutique", name="boutique")
     * @param \App\Controller\RequeteController $rctrl
     * @param Curl $crl
     * @return string|Response
     */
    function display()
    {
        $produits = '[{"nom_produit": "T-shirt","prix_produit" : "20"}, {"nom_produit": "Sweatshirt","prix_produit" : "30"}]';

        $produitsToDisplay = json_decode($produits);

        if (is_object($produitsToDisplay)) {
            $produits = '[' . $produits . ']';
            $produitsToDisplay = json_decode($produits);
        }

        try {
            return $this->render('produitDisplay.html.twig', [
                'produits' => $produitsToDisplay
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
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
