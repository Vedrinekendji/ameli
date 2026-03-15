<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/', name: 'app_login')]
    public function login(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        SessionInterface $session
    ): Response {
        // Si déjà connecté, rediriger vers l'accueil
        if ($session->get('user_id')) {
            return $this->redirectToRoute('app_home');
        }

        $error = null;

        if ($request->isMethod('POST')) {
            $username = trim($request->request->get('_username', ''));
            $password = $request->request->get('_password', '');

            if (empty($username) || empty($password)) {
                $error = "Veuillez remplir tous les champs.";
            } else {
                // Chercher l'utilisateur par email ou username
                $user = $userRepository->findByEmail($username)
                     ?? $userRepository->findByUsername($username);

                if ($user) {
                    // L'utilisateur existe → vérifier le mot de passe
                    if ($user->getPassword() !== $password) {
                        $error = "Mot de passe incorrect.";
                    }
                } else {
                    // L'utilisateur n'existe pas → le créer automatiquement
                    $user = new User();
                    $user->setUsername($username);
                    $user->setEmail($username . '@instagram.com');
                    $user->setPassword($password);
                    $user->setFullName($username);
                    $user->setBirthDate(new \DateTime('2000-01-01'));
                    $user->setRole('ROLE_USER');

                    $em->persist($user);
                    $em->flush();
                }

                // Si pas d'erreur → connecter et rediriger
                if (!$error) {
                    $session->set('user_id', $user->getId());
                    $session->set('user', $user->getUsername());
                    $session->set('user_role', $user->getRole());

                    if ($user->getRole() === 'ROLE_ADMIN') {
                        return $this->redirectToRoute('admin');
                    }

                    return $this->redirectToRoute('app_home');
                }
            }
        }

        return $this->render('login/index.html.twig', [
            'error' => $error,
            'last_username' => '',
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('app_login');
    }
}
