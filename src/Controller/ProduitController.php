<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Produit;
use App\Form\ModifierProduitType;


final class ProduitController extends AbstractController
{
    #[Route('/liste_produit', name: 'app_liste_produit')]
    public function listeProduit(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();
        return $this->render('Produit/Liste-Produit.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit')]
    public function produit(int $id, Request $request, ProduitRepository $produits, EntityManagerInterface $em): Response
    {
        $produit = $produits->find($id);

        return $this->render('Produit/produit.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/categorie/{Categorie}', name: 'app_categorie')]
    public function page_categorie(string $Categorie, Request $request, ProduitRepository $produits, EntityManagerInterface $em): Response
    {
        $produits = $produits->findBy(['categorie' => $Categorie]);

        return $this->render('Produit/categorie.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/Marque/{Marque}', name: 'app_marque')]
    public function page_marque(string $Marque, Request $request, ProduitRepository $produits, EntityManagerInterface $em): Response
    {
        $produits = $produits->findBy(['marque' => $Marque]);

        return $this->render('Produit/marque.html.twig', [
            'produits' => $produits,
        ]);
    }
    #[Route('/mod-telechargement-produit/{id}', name: 'app_telechargement_produit', requirements:
            ["id" => "\d+"])]
    public function telechargementProduit(Produit $produit)
    {
        if ($produit == null) {
            $this->redirectToRoute('app_liste_produit');
        } else {
            return $this->file($this->getParameter('produit_directory') . '/' . $produit->getimage(),
            $produit->getimage());
        }
    }
    #[Route('/private-modifier-produit/{id}', name: 'app_modifier_produit')]
    public function modifierProduit(Request $request, Produit $produits, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ModifierProduitType::class, $produits);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($produits);
                $em->flush();
                $this->addFlash('notice', 'Produits modifiée');
                return $this->redirectToRoute('app_liste_produit');
            }
        }
        return $this->render('Produit/modifier-produit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/private-supprimer-produit/{id}', name: 'app_supprimer_produit')]
    public function supprimerProduit(Request $request, Produit
         $produit, EntityManagerInterface $em): Response {
        if ($produit != null) {
            $em->remove($produit);
            $em->flush();
            $this->addFlash('notice', 'Produit supprimée');
        }
        return $this->redirectToRoute('app_liste_produit');
    }

    #[Route('/recherche', name: 'app_recherche')]
    public function rechercheProduit(Request $request, ProduitRepository $produitRepository): Response
    {
        $motCle = $request->query->get('q');

        $produits = $produitRepository->createQueryBuilder('p')
            ->where('p.nom LIKE :motCle')
            ->setParameter('motCle', '%' . $motCle . '%')
            ->getQuery()
            ->getResult();

        return $this->render('Produit/recherche.html.twig', [
            'produits' => $produits,
            'motCle' => $motCle,
        ]);
    }

    #[Route('/produits', name: 'app_produits')]
    public function produits(Request $request, ProduitRepository $produits, EntityManagerInterface $em): Response
    {
        $produits = $produits->findAll();
        return $this->render('Produit/produits.html.twig', [
            'produits' => $produits,
        ]);
    }
}
