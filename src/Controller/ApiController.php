<?php

namespace App\Controller;

use App\Entity\Etablissement;
use App\Entity\Prescription;
use App\Entity\RendezVous;
use App\Entity\ResultatAnalyse;
use App\Entity\TypeIntervention;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ApiController extends AbstractController
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

        return new JsonResponse($data);
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

        return new JsonResponse($data);
    }

    #[IsGranted('ROLE_PATIENT')]
    #[Route('/api/patient/rendez-vous', name: 'api_patient_rendezvous', methods: ['GET'])]
    public function patientRendezVous(EntityManagerInterface $entityManager): JsonResponse
    {
        $patient = $this->getUser();

        if (!$patient instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non reconnu.'], 403);
        }

        // On filtre uniquement les rendez-vous du patient connecté.
        $rendezVousList = $entityManager->getRepository(RendezVous::class)->findBy(
            ['patient' => $patient],
            ['dateHeure' => 'ASC']
        );

        $data = array_map(static function (RendezVous $rendezVous): array {
            $professionnel = $rendezVous->getProfessionnel();
            $etablissement = $rendezVous->getEtablissement();
            $typeIntervention = $rendezVous->getTypeIntervention();

            return [
                'id' => $rendezVous->getId(),
                'dateHeure' => $rendezVous->getDateHeure()?->format('Y-m-d H:i:s'),
                'statut' => $rendezVous->getStatut(),
                'etablissement' => $etablissement?->getNom(),
                'typeIntervention' => $typeIntervention?->getLibelle(),
                'professionnel' => $professionnel ? trim(($professionnel->getPrenom() ?? '').' '.($professionnel->getNom() ?? '')) : null,
            ];
        }, $rendezVousList);

        return new JsonResponse($data);
    }

    #[IsGranted('ROLE_PATIENT')]
    #[Route('/api/patient/prescriptions', name: 'api_patient_prescriptions', methods: ['GET'])]
    public function patientPrescriptions(EntityManagerInterface $entityManager): JsonResponse
    {
        $patient = $this->getUser();

        if (!$patient instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non reconnu.'], 403);
        }

        // On filtre uniquement les prescriptions du patient connecté.
        $prescriptions = $entityManager->getRepository(Prescription::class)->findBy(
            ['patient' => $patient],
            ['datePrescription' => 'DESC']
        );

        $data = array_map(static function (Prescription $prescription): array {
            $professionnel = $prescription->getProfessionnel();

            return [
                'id' => $prescription->getId(),
                'datePrescription' => $prescription->getDatePrescription()?->format('Y-m-d'),
                'contenu' => $prescription->getContenu(),
                'professionnel' => $professionnel ? trim(($professionnel->getPrenom() ?? '').' '.($professionnel->getNom() ?? '')) : null,
            ];
        }, $prescriptions);

        return new JsonResponse($data);
    }

    #[IsGranted('ROLE_PATIENT')]
    #[Route('/api/patient/resultats', name: 'api_patient_resultats', methods: ['GET'])]
    public function patientResultats(EntityManagerInterface $entityManager): JsonResponse
    {
        $patient = $this->getUser();

        if (!$patient instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non reconnu.'], 403);
        }

        // On filtre uniquement les resultats d'analyse du patient connecté.
        $resultats = $entityManager->getRepository(ResultatAnalyse::class)->findBy(
            ['patient' => $patient],
            ['dateAnalyse' => 'DESC']
        );

        $data = array_map(static function (ResultatAnalyse $resultat): array {
            return [
                'id' => $resultat->getId(),
                'titre' => $resultat->getTitre(),
                'typeAnalyse' => $resultat->getTypeAnalyse(),
                'dateAnalyse' => $resultat->getDateAnalyse()?->format('Y-m-d'),
                'statut' => $resultat->getStatut(),
                'commentaire' => $resultat->getCommentaire(),
            ];
        }, $resultats);

        return new JsonResponse($data);
    }
}

