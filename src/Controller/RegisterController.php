<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepository
    ): Response {
        $error = null;

        if ($request->isMethod('POST')) {
            $email = trim($request->request->get('email', ''));
            $password = $request->request->get('password', '');
            $fullName = trim($request->request->get('full_name', ''));
            $username = trim($request->request->get('username', ''));
            $day = $request->request->get('birth_day', '');
            $month = $request->request->get('birth_month', '');
            $year = $request->request->get('birth_year', '');

            if (empty($email) || empty($password) || empty($fullName) || empty($username) || empty($day) || empty($month) || empty($year)) {
                $error = "Veuillez remplir tous les champs.";
            } elseif (strlen($password) < 6) {
                $error = "Le mot de passe doit contenir au moins 6 caractères.";
            } elseif ($userRepository->findByEmail($email)) {
                $error = "Cette adresse e-mail est déjà utilisée.";
            } elseif ($userRepository->findByUsername($username)) {
                $error = "Ce nom de profil est déjà utilisé.";
            } else {
                $user = new User();
                $user->setEmail($email);
                $user->setPassword($password);
                $user->setFullName($fullName);
                $user->setUsername($username);
                $user->setBirthDate(new \DateTime("$year-$month-$day"));
                $user->setRole('ROLE_USER');

                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('register/index.html.twig', [
            'error' => $error,
        ]);
    }
}
