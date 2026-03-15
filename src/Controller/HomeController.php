<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(SessionInterface $session): Response
    {
        $user = $session->get('user');

        // Si pas connecté, rediriger vers le login
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('home/index.html.twig', [
            'username' => $user,
        ]);
    }
}
