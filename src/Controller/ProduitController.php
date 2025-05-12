<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


final class ProduitController extends AbstractController
{
    #[Route('/liste_produit', name: 'app_liste_produit')]
    public function listeProduit(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();
        return $this->render('Produit/Liste-Produit.html.twig', [
            'produits' => $produits
        ]);
    }

    #[Route('/produits/{id}', name: 'app_produits')]
    public function produit(int $id, Request $request, ProduitRepository $produits, EntityManagerInterface $em): Response
    {
        $produit = $produits->find($id);

        return $this->render('base/accueil.html.twig', [
            'produit' => $produit,
        ]);
    }
}
