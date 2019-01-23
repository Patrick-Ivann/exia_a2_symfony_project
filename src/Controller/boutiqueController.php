<?php
namespace App\Controller;
use App\Entity\Idee;
use App\Entity\Produit;
use App\Form\IdeeFormType;
use App\Form\ProduitFormType;
use App\services\Curl;
use App\Controller\RequeteController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            ]);

            $file = $req->files->get("produit_form")["photo_produit"];

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
     * @Route("/shopGet")
     *
     */
    function display(RequeteController $rctrl,Curl $crl)
    {
        $produits = $rctrl->recupererTousLesProduits($crl);

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
}
?>