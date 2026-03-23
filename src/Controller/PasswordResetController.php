<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PasswordResetController extends AbstractController
{
    #[Route('/password-reset', name: 'app_password_reset')]
    public function passwordReset(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em
    ): Response
    {
        $error = null;
        $success = null;

        if ($request->isMethod('POST')) {
            $identifier = $request->request->get('identifier');
            $newPassword = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            $user = $userRepository->findByEmail($identifier);

            if (!$user) {
                $error = "Aucun compte trouvé.";
            } elseif ($newPassword !== $confirmPassword) {
                $error = "Les mots de passe ne correspondent pas.";
            } elseif (strlen($newPassword) < 6) {
                $error = "Minimum 6 caractères.";
            } else {
                $user->setPassword($newPassword);
                $em->flush();
                $success = "Mot de passe modifié avec succès !";
            }
        }

        return $this->render('password/reset.html.twig', [
            'error' => $error,
            'success' => $success
        ]);
    }
}