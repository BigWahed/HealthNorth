<?php

namespace App\Controller\Api;

use App\Entity\Etablissement;
use App\Entity\TypeIntervention;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class PublicApiController extends AbstractController
{
    #[Route('/api/etablissements', name: 'api_etablissements', methods: ['GET'])]
    public function etablissements(EntityManagerInterface $entityManager): JsonResponse
    {
        $etablissements = $entityManager->getRepository(Etablissement::class)->findBy([], ['nom' => 'ASC']);

        $data = array_map(static function (Etablissement $etablissement): array {
            return [
                'id' => $etablissement->getId(),
                'nom' => $etablissement->getNom(),
                'type' => $etablissement->getType(),
                'adresse' => $etablissement->getAdresse(),
                'ville' => $etablissement->getVille(),
                'codePostal' => $etablissement->getCodePostal(),
            ];
        }, $etablissements);

        return new JsonResponse([
            'success' => true,
            'etablissements' => $data,
        ]);
    }

    #[Route('/api/types-intervention', name: 'api_types_intervention', methods: ['GET'])]
    public function typesIntervention(EntityManagerInterface $entityManager): JsonResponse
    {
        $types = $entityManager->getRepository(TypeIntervention::class)->findBy([], ['libelle' => 'ASC']);

        $data = array_map(static function (TypeIntervention $type): array {
            return [
                'id' => $type->getId(),
                'libelle' => $type->getLibelle(),
                'description' => $type->getDescription(),
            ];
        }, $types);

        return new JsonResponse([
            'success' => true,
            'types' => $data,
        ]);
    }
}

