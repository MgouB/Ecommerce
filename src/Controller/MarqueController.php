<?php
namespace App\Controller;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Form\ModifierMarqueType;
use App\Repository\MarqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MarqueController extends AbstractController
{
    #[Route('/private-liste_marque', name: 'app_liste_marque', methods: ['GET', 'POST'])]
    public function listeMarque(Request $request, MarqueRepository $marqueRepository,
        EntityManagerInterface $em): Response {

        $marques = $marqueRepository->findAll();
        return $this->render('marque/liste_marque.html.twig', [
            'marques' => $marques,

        ]);
    }

    #[Route('/private-modifier-marque/{id}', name: 'app_modifier_marque')]
    public function modifierMarque(Request $request, Marque $marques, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ModifierMarqueType::class, $marques);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($marques);
                $em->flush();
                $this->addFlash('notice', 'Marques modifiée');
                return $this->redirectToRoute('app_liste_marque');
            }
        }
        return $this->render('marque/modifier-marque.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/private-supprimer-marque/{id}', name: 'app_supprimer_marque')]
    public function supprimerMarque(Request $request, Marque
         $marque, EntityManagerInterface $em): Response {
        if ($marque != null) {
            $em->remove($marque);
            $em->flush();
            $this->addFlash('notice', 'Marque supprimée');
        }
        return $this->redirectToRoute('app_liste_marque');
    }

    #[Route('/ajout-marque', name: 'app_ajout_marque')]
    public function ajoutMarque(Request $request, EntityManagerInterface $em): Response
    {
        $marque = new Marque();
        $form = $this->createForm(MarqueType::class, $marque);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form->get('imageMarque')->getData();
                if ($file) {
                    $fileName = uniqid() . '.' . $file->guessExtension();
                    $file->move($this->getParameter('marque_directory'), $fileName);
                    $marque->setimageMarque($fileName);
                }
                $em->persist($marque);
                $em->flush();
                $this->addFlash('notice', 'Marque Ajouté');
                return $this->redirectToRoute('app_ajout_marque');
            }
        }

        return $this->render('marque/ajout_marque.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/mod-telechargement-marque/{id}', name: 'app_telechargement_marque', requirements:
            ["id" => "\d+"])]
    public function telechargementMarque(Marque $marque)
    {
        if ($marque == null) {
            $this->redirectToRoute('app_liste_marque');
        } else {
            return $this->file($this->getParameter('marque_directory') . '/' . $marque->getImageMarque(),
                $marque->getImageMarque());
        }
    }
}
