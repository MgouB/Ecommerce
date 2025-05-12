<?php
namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;


class BaseController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function accueil(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();
        return $this->render('base/accueil.html.twig', [
            'produits' => $produits
        ]);
    }

    #[Route('/nouveautes', name: 'app_nouveautes')]
    public function nouveautes(): Response
    {
        return $this->render('base/nouveautes.html.twig', [
        ]);
    }
    #[Route('/ajout-produit', name: 'app_ajout_produit')]
    public function produit(Request $request, EntityManagerInterface $em): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $produit->setDateEnvoi(new \Datetime());
                $em->persist($produit);
                $em->flush();
                $this->addFlash('notice', 'Message envoyé');
                return $this->redirectToRoute('app_ajout_produit');
            }
        }

        return $this->render('Produit/ajout-produit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/a propos', name: 'app_apropos')]
    public function apropos(): Response
    {
        return $this->render('base/apropos.html.twig', [
        ]);
    }
    #[Route('/mentioslegale', name: 'app_mentionslegale')]
    public function mentionslegale(): Response
    {
        return $this->render('base/mentionslegale.html.twig', [
        ]);
    }
    #[Route('/private-profil', name: 'app_profil')]
    public function profil(): Response
    {
        return $this->render('utilisateurs/profil.html.twig', [
        ]);

    }
    #[Route('/aimer/{id}', name: 'app_aimer_produit')]
    public function aimerProduit(Request $request, Produit $produit, EntityManagerInterface $em): Response
    {
        $referer = $request ->headers->get('referer');

        if ($produit) {
            $user = $this->getUser();

            if (!$user->getProduits() ->contains($produit)) { 
                $user ->addProduit($produit);
            } else {
                $user->removeProduit($produit);
            }
            $em->persist($user);
            $em->flush();
        }
        return $this->redirect($referer ?? $this->generateUrl('app_accueil'));   
    }
    #[Route('/favoris', name: 'app_favoris')]
    public function favoris(): Response
    {
        $user = $this->getUser();
        $produitsFavoris = $user->getProduits();
        return $this->render('Produit/favoris.html.twig', [
            'produits' => $produitsFavoris,
        ]);

    }
}

