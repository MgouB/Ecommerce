<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;

final class UtilisateursController extends AbstractController
{
    #[Route('/mod-utilisateurs', name: 'app_utilisateurs')]
    public function listeUtilisateurs(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('utilisateurs/liste-utilisateurs.html.twig', [
        'users' => $users    
        ]);
    }
}
