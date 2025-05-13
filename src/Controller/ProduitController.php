<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
}
