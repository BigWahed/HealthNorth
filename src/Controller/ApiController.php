<?php

namespace App\Controller;

use App\Entity\Etablissement;
use App\Entity\Prescription;
use App\Entity\PriseMedicament;
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
    #[Route('/api/mobile/patient/{id}/dossier', name: 'api_mobile_patient_dossier', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function mobilePatientDossier(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        // Version mobile basee sur l'identifiant passe dans l'URL.
        // En production, il faut une authentification par token securise.
        $patient = $entityManager->getRepository(User::class)->find($id);

        if (!$patient instanceof User) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Patient introuvable',
            ], 404);
        }

        return new JsonResponse([
            'id' => $patient->getId(),
            'nom' => $patient->getNom(),
            'prenom' => $patient->getPrenom(),
            'email' => $patient->getEmail(),
            'telephone' => $patient->getTelephone(),
            'adresse' => $patient->getAdresse(),
            'dateNaissance' => $patient->getDateNaissance()?->format('Y-m-d'),
            'photo' => $patient->getPhoto(),
            'numeroSecuriteSociale' => $patient->getNumeroSecuriteSociale(),
            'personneContact' => $patient->getPersonneContact(),
            'telephonePersonneContact' => $patient->getTelephonePersonneContact(),
            'medecinTraitant' => $patient->getMedecinTraitant(),
        ]);
    }

    #[Route('/api/mobile/patient/{id}/rendez-vous', name: 'api_mobile_patient_rendezvous', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function mobilePatientRendezVous(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $patient = $entityManager->getRepository(User::class)->find($id);

        if (!$patient instanceof User) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Patient introuvable',
            ], 404);
        }

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

    #[Route('/api/mobile/patient/{id}/options', name: 'api_mobile_patient_options', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function mobilePatientOptions(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $patient = $entityManager->getRepository(User::class)->find($id);

        if (!$patient instanceof User) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Patient introuvable',
            ], 404);
        }

        $types = $entityManager->getRepository(TypeIntervention::class)->findBy([], ['libelle' => 'ASC']);

        $data = array_map(static function (TypeIntervention $type): array {
            return [
                'id' => $type->getId(),
                'libelle' => $type->getLibelle(),
                'description' => $type->getDescription(),
                // Statut fixe.
                'statut' => 'actif',
            ];
        }, $types);

        return new JsonResponse($data);
    }

    #[Route('/api/mobile/patient/{id}/alarmes-medicaments', name: 'api_mobile_patient_alarmes_medicaments', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function mobilePatientAlarmesMedicaments(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $patient = $entityManager->getRepository(User::class)->find($id);

        if (!$patient instanceof User) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Patient introuvable',
            ], 404);
        }

        $prisesMedicaments = $entityManager->getRepository(PriseMedicament::class)->findBy(
            ['patient' => $patient],
            ['id' => 'DESC']
        );

        $data = array_map(static function (PriseMedicament $prise): array {
            return [
                'medicament' => $prise->getMedicament()?->getNom(),
                'posologie' => $prise->getPosologie(),
                'frequence' => $prise->getFrequence(),
                'momentPrise' => $prise->getMomentPrise(),
            ];
        }, $prisesMedicaments);

        return new JsonResponse($data);
    }

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

    #[Route('/api/patient/options', name: 'api_patient_options', methods: ['GET'])]
    public function patientOptions(EntityManagerInterface $entityManager): JsonResponse
    {
        $etablissements = $entityManager->getRepository(Etablissement::class)->findBy([], ['nom' => 'ASC']);
        $types = $entityManager->getRepository(TypeIntervention::class)->findBy([], ['libelle' => 'ASC']);

        $etablissementsData = array_map(static function (Etablissement $etablissement): array {
            return [
                'id' => $etablissement->getId(),
                'nom' => $etablissement->getNom(),
            ];
        }, $etablissements);

        $typesData = array_map(static function (TypeIntervention $type): array {
            return [
                'id' => $type->getId(),
                'libelle' => $type->getLibelle(),
            ];
        }, $types);

        return new JsonResponse([
            'etablissements' => $etablissementsData,
            'typesIntervention' => $typesData,
        ]);
    }

    #[IsGranted('ROLE_PATIENT')]
    #[Route('/api/patient/dossier', name: 'api_patient_dossier', methods: ['GET'])]
    public function patientDossier(): JsonResponse
    {
        $patient = $this->getUser();

        if (!$patient instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non reconnu.'], 403);
        }

        // Cette route expose uniquement le dossier du patient connecte.
        // La connexion reste basee sur email + mot de passe (pas sur le numero de securite sociale).
        return new JsonResponse([
            'id' => $patient->getId(),
            'nom' => $patient->getNom(),
            'prenom' => $patient->getPrenom(),
            'email' => $patient->getEmail(),
            'telephone' => $patient->getTelephone(),
            'adresse' => $patient->getAdresse(),
            'dateNaissance' => $patient->getDateNaissance()?->format('Y-m-d'),
            'photo' => $patient->getPhoto(),
            'numeroSecuriteSociale' => $patient->getNumeroSecuriteSociale(),
            'personneContact' => $patient->getPersonneContact(),
            'telephonePersonneContact' => $patient->getTelephonePersonneContact(),
            'medecinTraitant' => $patient->getMedecinTraitant(),
        ]);
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

    #[Route('/api/patient/alarmes-medicaments', name: 'api_patient_alarmes_medicaments', methods: ['GET'])]
    public function patientAlarmesMedicaments(EntityManagerInterface $entityManager): JsonResponse
    {
        $patient = $this->getUser();

        if (!$patient instanceof User) {
            return new JsonResponse(['message' => 'Utilisateur non reconnu.'], 403);
        }

        $prisesMedicaments = $entityManager->getRepository(PriseMedicament::class)->findBy(
            ['patient' => $patient],
            ['id' => 'DESC']
        );

        $data = array_map(static function (PriseMedicament $prise): array {
            return [
                'id' => $prise->getId(),
                'medicament' => $prise->getMedicament()?->getNom(),
                'posologie' => $prise->getPosologie(),
                'frequence' => $prise->getFrequence(),
                'momentPrise' => $prise->getMomentPrise(),
            ];
        }, $prisesMedicaments);

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

        // On filtre uniquement les résultats d’analyse du patient connecté.
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
