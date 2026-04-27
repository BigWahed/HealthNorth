<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function apiLogin(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse
    {
        // 1) On lit le JSON envoye par l'application mobile.
        $payload = json_decode($request->getContent(), true);

        // Si le JSON est invalide ou vide, on renvoie la meme erreur.
        if (!\is_array($payload)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Identifiants incorrects',
            ], 401);
        }

        // 2) On recupere email + password (valeurs vides si absentes).
        $email = (string) ($payload['email'] ?? '');
        $password = (string) ($payload['password'] ?? '');

        // 3) On cherche l'utilisateur par email.
        $user = $userRepository->findOneBy(['email' => $email]);

        // 4) On verifie le mot de passe avec le hasher Symfony.
        // Si l'utilisateur n'existe pas OU mot de passe incorrect => meme message.
        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Identifiants incorrects',
            ], 401);
        }

        // 5) Si OK, on renvoie les informations utiles pour l'app mobile.
        // Note: en production, utiliser une authentification par token securise.
        return new JsonResponse([
            'success' => true,
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'roles' => $user->getRoles(),
            ],
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): never
    {
        throw new \LogicException('Cette méthode est interceptée par le firewall logout de Symfony.');
    }
}

