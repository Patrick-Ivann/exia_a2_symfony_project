<?php
namespace App\Controller;
use App\Entity\Idee;
use App\Entity\Panier;
use App\Entity\Produit;
use App\Form\IdeeFormType;
use App\Form\PanierFormType;
use App\Form\ProduitFormType;
use App\services\Curl;
use App\Controller\RequeteController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
            ]);

            $file = $req->files->get("produit_form")["photo_produit"];

            $type = 'produit';

            $rctrl->ajouterProduit($produitDataToSend, $file, $type ,$crl);
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
    function display(RequeteController $rctrl,Curl $crl, Request $req, SessionInterface $session)
    {
        $id_user = $session->get("id_user");

        $produits = $rctrl->recupererTousLesProduits($crl);

        $produitsToDisplay = json_decode($produits);

        if (is_object($produitsToDisplay)) {
            $produits = '[' . $produits . ']';
            $produitsToDisplay = json_decode($produits);
        }

        $formPanier = $this->panier($rctrl, $req,$produitsToDisplay, $crl, $id_user);

        try {
            return $this->render('produitDisplay.html.twig', [
                'produits' => $produitsToDisplay,
                'form' => $formPanier->createView()
            ]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

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

        $formFinal = $form->getForm();

        $formFinal->handleRequest($req);

        if ($formFinal->isSubmitted() && $formFinal->isValid()) {

            $dataPanier = $formFinal->getData();

            foreach ($produitToDisplay as $key=>$produit)
            {
                $nom_article = 'article'.$produit->id_produit;
                $dataPanier->$nom_article;

                if($dataPanier->$nom_article)
                {

                    $panierDataToSend = json_encode([
                        'id_user' => $id_user,
                        'id_produit' => $produit->id_produit
                    ]);
                    $rctrl->acheter($panierDataToSend, $crl);
                }

               //
            }




        }

         return $formFinal;
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
