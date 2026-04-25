<?php

namespace App\Controller;

use App\Entity\PriseMedicament;
use App\Entity\Prescription;
use App\Entity\ResultatAnalyse;
use App\Entity\RendezVous;
use App\Entity\User;
use App\Form\PrescriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// Toutes les routes de ce controleur sont protegees pour ROLE_PRO.
#[IsGranted('ROLE_PRO')]
class ProController extends AbstractController
{
    #[Route('/pro/prescription/nouvelle', name: 'pro_prescription_new')]
    public function nouvellePrescription(Request $request, EntityManagerInterface $entityManager): Response
    {
        $professionnel = $this->getUser();

        if (!$professionnel instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur professionnel non reconnu.');
        }

        $patients = $this->getPatients($entityManager);

        $prescription = new Prescription();
        $prescription->setDatePrescription(new \DateTimeImmutable('today'));

        $form = $this->createForm(PrescriptionType::class, $prescription, [
            'patients' => $patients,
            'show_patient' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $patient = $prescription->getPatient();

            if (!$patient instanceof User || !\in_array('ROLE_PATIENT', $patient->getRoles(), true)) {
                $this->addFlash('danger', 'Le patient sélectionné est invalide.');

                return $this->redirectToRoute('pro_prescription_new');
            }

            // Le professionnel connecté est associé automatiquement à la prescription.
            $prescription->setProfessionnel($professionnel);

            $entityManager->persist($prescription);
            $entityManager->flush();

            $this->addFlash('success', 'Prescription ajoutée avec succès.');

            return $this->redirectToRoute('pro_patient_show', ['id' => $patient->getId()]);
        }

        return $this->render('pro/prescription/new.html.twig', [
            'form' => $form->createView(),
            'patient' => null,
        ]);
    }

    #[Route('/pro/patient/{id}/prescription/nouvelle', name: 'pro_patient_prescription_new', requirements: ['id' => '\d+'])]
    public function nouvellePrescriptionPourPatient(User $patient, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!\in_array('ROLE_PATIENT', $patient->getRoles(), true)) {
            throw $this->createNotFoundException('Cet utilisateur n\'est pas un patient.');
        }

        $professionnel = $this->getUser();

        if (!$professionnel instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur professionnel non reconnu.');
        }

        $prescription = new Prescription();
        $prescription->setPatient($patient);
        $prescription->setDatePrescription(new \DateTimeImmutable('today'));

        $form = $this->createForm(PrescriptionType::class, $prescription, [
            'show_patient' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Le professionnel connecté est associé automatiquement à la prescription.
            $prescription->setProfessionnel($professionnel);
            $prescription->setPatient($patient);

            $entityManager->persist($prescription);
            $entityManager->flush();

            $this->addFlash('success', 'Prescription ajoutée avec succès.');

            return $this->redirectToRoute('pro_patient_show', ['id' => $patient->getId()]);
        }

        return $this->render('pro/prescription/new.html.twig', [
            'form' => $form->createView(),
            'patient' => $patient,
        ]);
    }

    #[Route('/pro/rendez-vous', name: 'pro_rendezvous')]
    public function mesRendezVous(EntityManagerInterface $entityManager): Response
    {
        $professionnel = $this->getUser();

        if (!$professionnel instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur professionnel non reconnu.');
        }

        // On filtre les rendez-vous avec le professionnel connecte.
        $rendezVousList = $entityManager->getRepository(RendezVous::class)->findBy(
            ['professionnel' => $professionnel],
            ['dateHeure' => 'ASC']
        );

        return $this->render('pro/rendezvous/index.html.twig', [
            'rendezVousList' => $rendezVousList,
        ]);
    }

    #[Route('/pro/patients', name: 'pro_patients')]
    public function mesPatients(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        // On garde uniquement les utilisateurs avec le role patient.
        $patients = array_values(array_filter(
            $users,
            static fn (User $user): bool => \in_array('ROLE_PATIENT', $user->getRoles(), true)
        ));

        usort($patients, static function (User $a, User $b): int {
            return strcmp(
                ($a->getNom() ?? '').($a->getPrenom() ?? ''),
                ($b->getNom() ?? '').($b->getPrenom() ?? '')
            );
        });

        return $this->render('pro/patients/index.html.twig', [
            'patients' => $patients,
        ]);
    }

    #[Route('/pro/patient/{id}', name: 'pro_patient_show', requirements: ['id' => '\d+'])]
    public function voirPatient(User $patient, EntityManagerInterface $entityManager): Response
    {
        if (!\in_array('ROLE_PATIENT', $patient->getRoles(), true)) {
            throw $this->createNotFoundException('Ce dossier n\'est pas un patient.');
        }

        $rendezVousList = $entityManager->getRepository(RendezVous::class)->findBy(
            ['patient' => $patient],
            ['dateHeure' => 'DESC']
        );

        $prescriptions = $entityManager->getRepository(Prescription::class)->findBy(
            ['patient' => $patient],
            ['datePrescription' => 'DESC']
        );

        $resultatsAnalyse = $entityManager->getRepository(ResultatAnalyse::class)->findBy(
            ['patient' => $patient],
            ['dateAnalyse' => 'DESC']
        );

        $prisesMedicaments = $entityManager->getRepository(PriseMedicament::class)->findBy(
            ['patient' => $patient],
            ['id' => 'DESC']
        );

        return $this->render('pro/patient/show.html.twig', [
            'patient' => $patient,
            'rendezVousList' => $rendezVousList,
            'prescriptions' => $prescriptions,
            'resultatsAnalyse' => $resultatsAnalyse,
            'prisesMedicaments' => $prisesMedicaments,
        ]);
    }

    #[Route('/pro/resultats', name: 'pro_resultats')]
    public function mesResultats(EntityManagerInterface $entityManager): Response
    {
        $resultatsAnalyse = $entityManager->getRepository(ResultatAnalyse::class)->findBy(
            [],
            ['dateAnalyse' => 'DESC']
        );

        return $this->render('pro/resultats/index.html.twig', [
            'resultatsAnalyse' => $resultatsAnalyse,
        ]);
    }

    /**
     * Récupère uniquement les utilisateurs ayant ROLE_PATIENT.
     */
    private function getPatients(EntityManagerInterface $entityManager): array
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        $patients = array_values(array_filter(
            $users,
            static fn (User $user): bool => \in_array('ROLE_PATIENT', $user->getRoles(), true)
        ));

        usort($patients, static function (User $a, User $b): int {
            return strcmp(
                ($a->getNom() ?? '').($a->getPrenom() ?? ''),
                ($b->getNom() ?? '').($b->getPrenom() ?? '')
            );
        });

        return $patients;
    }
}
