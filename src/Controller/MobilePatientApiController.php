<?php

namespace App\Controller;

use App\Entity\Prescription;
use App\Entity\PriseMedicament;
use App\Entity\ResultatAnalyse;
use App\Entity\RendezVous;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class MobilePatientApiController extends AbstractController
{
    #[Route('/api/mobile/patient/{id}/dossier', name: 'api_mobile_patient_dossier', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function dossierPatient(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $patient = $this->findPatient($id, $entityManager);
        if ($patient instanceof JsonResponse) {
            return $patient;
        }

        $prescriptions = $entityManager->getRepository(Prescription::class)->findBy(
            ['patient' => $patient],
            ['datePrescription' => 'DESC']
        );

        $resultatsAnalyses = $entityManager->getRepository(ResultatAnalyse::class)->findBy(
            ['patient' => $patient],
            ['dateAnalyse' => 'DESC']
        );

        $prescriptionsData = array_map(static function (Prescription $prescription): array {
            $professionnel = $prescription->getProfessionnel();

            return [
                'id' => $prescription->getId(),
                'datePrescription' => $prescription->getDatePrescription()?->format('Y-m-d'),
                'contenu' => $prescription->getContenu(),
                'professionnel' => $professionnel ? trim(($professionnel->getPrenom() ?? '') . ' ' . ($professionnel->getNom() ?? '')) : null,
            ];
        }, $prescriptions);

        $resultatsData = array_map(static function (ResultatAnalyse $resultat): array {
            return [
                'id' => $resultat->getId(),
                'titre' => $resultat->getTitre(),
                'typeAnalyse' => $resultat->getTypeAnalyse(),
                'dateAnalyse' => $resultat->getDateAnalyse()?->format('Y-m-d'),
                'statut' => $resultat->getStatut(),
                'commentaire' => $resultat->getCommentaire(),
            ];
        }, $resultatsAnalyses);

        return new JsonResponse([
            'success' => true,
            'patient' => [
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
            ],
            'prescriptions' => $prescriptionsData,
            'resultatsAnalyses' => $resultatsData,
        ]);
    }

    #[Route('/api/mobile/patient/{id}/rendez-vous', name: 'api_mobile_patient_rendezvous', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function rendezVousPatient(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $patient = $this->findPatient($id, $entityManager);
        if ($patient instanceof JsonResponse) {
            return $patient;
        }

        $rendezVousList = $entityManager->getRepository(RendezVous::class)->findBy(
            ['patient' => $patient],
            ['dateHeure' => 'ASC']
        );

        $data = array_map(static function (RendezVous $rendezVous): array {
            $professionnel = $rendezVous->getProfessionnel();

            return [
                'id' => $rendezVous->getId(),
                'dateHeure' => $rendezVous->getDateHeure()?->format('Y-m-d H:i:s'),
                'statut' => $rendezVous->getStatut(),
                'etablissement' => $rendezVous->getEtablissement()?->getNom(),
                'typeIntervention' => $rendezVous->getTypeIntervention()?->getLibelle(),
                'professionnel' => $professionnel ? trim(($professionnel->getPrenom() ?? '') . ' ' . ($professionnel->getNom() ?? '')) : null,
            ];
        }, $rendezVousList);

        return new JsonResponse([
            'success' => true,
            'rendezVous' => $data,
        ]);
    }

    #[Route('/api/mobile/patient/{id}/options', name: 'api_mobile_patient_options', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function optionsPatient(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $patient = $this->findPatient($id, $entityManager);
        if ($patient instanceof JsonResponse) {
            return $patient;
        }

        // Options statiques simples pour le BTS (sans table supplementaire).
        return new JsonResponse([
            'success' => true,
            'options' => [
                [
                    'id' => 1,
                    'libelle' => 'Rappel avant rendez-vous',
                    'description' => 'Recevoir un rappel avant un rendez-vous medical',
                    'statut' => 'Actif',
                ],
                [
                    'id' => 2,
                    'libelle' => 'Contact d\'urgence',
                    'description' => 'Personne a prevenir en cas de besoin',
                    'statut' => 'Actif',
                ],
            ],
        ]);
    }

    #[Route('/api/mobile/patient/{id}/alarmes-medicaments', name: 'api_mobile_patient_alarmes_medicaments', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function alarmesMedicamentsPatient(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $patient = $this->findPatient($id, $entityManager);
        if ($patient instanceof JsonResponse) {
            return $patient;
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

        return new JsonResponse([
            'success' => true,
            'alarmes' => $data,
        ]);
    }

    private function findPatient(int $id, EntityManagerInterface $entityManager): User|JsonResponse
    {
        $patient = $entityManager->getRepository(User::class)->find($id);
        if ($patient instanceof User) {
            return $patient;
        }

        return new JsonResponse([
            'success' => false,
            'message' => 'Patient introuvable',
        ], 404);
    }
}
