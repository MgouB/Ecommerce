<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\User;
use App\Form\ModifierUserType;
use App\Form\SupprimerUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UtilisateursController extends AbstractController
{
    #[Route('/mod-liste-users', name: 'app_liste_users')]
    public function listeUsers(Request $request, UserRepository $UserRepository, EntityManagerInterface $em): Response
    {
        $users = $UserRepository->findAll();
        $form = $this->createForm(SupprimerUserType::class, null, [
            'users' => $users,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedUsers = $form->get('users')->getData();
            foreach ($selectedUsers as $user) {
                $em->remove($user);
            }
            $em->flush();
            $this->addFlash('notice', 'Utilisateur supprimées avec succès');
            return $this->redirectToRoute('app_liste_users');
        }

        return $this->render('utilisateurs/liste-utilisateurs.html.twig', [
            'users' => $users,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/mod-modifier-user/{id}', name: 'app_modifier_user')]
    public function modifierUser(Request $request, User $user, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ModifierUserType::class, $user);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($user);
                $em->flush();
                $this->addFlash('notice', 'Utilisateur modifiée');
                return $this->redirectToRoute('app_liste_users');
            }
        }
        return $this->render('utilisateurs/modifier_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/mod-supprimer-user/{id}', name: 'app_supprimer_user')]
    public function supprimerUser(Request $request, User $user, EntityManagerInterface $em): Response
    {
        if ($user != null) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('notice', 'Utilisateur supprimée');
        }
        return $this->redirectToRoute('app_liste_users');
    }
}
