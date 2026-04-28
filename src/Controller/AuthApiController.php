<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AuthApiController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        // Le mobile envoie email/password en JSON.
        $payload = json_decode($request->getContent(), true);

        if (!\is_array($payload)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Identifiants incorrects',
            ], 401);
        }

        $email = (string) ($payload['email'] ?? '');
        $password = (string) ($payload['password'] ?? '');

        $user = $userRepository->findOneBy(['email' => $email]);
        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Identifiants incorrects',
            ], 401);
        }

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
}
