<?php

namespace App\Controller;

use App\Entity\Prescription;
use App\Entity\PriseMedicament;
use App\Entity\ResultatAnalyse;
use App\Entity\RendezVous;
use App\Entity\User;
use App\Form\RendezVousType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_PATIENT')]
class PatientController extends AbstractController
{
    #[Route('/patient/dossier', name: 'patient_dossier')]
    public function dossierPatient(EntityManagerInterface $entityManager): Response
    {
        $patient = $this->getUser();

        if (!$patient instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur patient non reconnu.');
        }

        // Important : on filtre toutes les données par l'utilisateur connecté.
        // Ainsi, un patient ne voit que son propre dossier.
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

        return $this->render('patient/dossier.html.twig', [
            'patient' => $patient,
            'rendezVousList' => $rendezVousList,
            'prescriptions' => $prescriptions,
            'resultatsAnalyse' => $resultatsAnalyse,
            'prisesMedicaments' => $prisesMedicaments,
        ]);
    }

    #[Route('/patient/rendez-vous/nouveau', name: 'patient_rendezvous_new')]
    public function nouveauRendezVous(Request $request, EntityManagerInterface $entityManager): Response
    {
        $patient = $this->getUser();

        if (!$patient instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur patient non reconnu.');
        }

        $professionnels = array_values(array_filter(
            $entityManager->getRepository(User::class)->findAll(),
            static fn (User $user): bool => \in_array('ROLE_PRO', $user->getRoles(), true)
        ));

        $rendezVous = new RendezVous();

        $form = $this->createForm(RendezVousType::class, $rendezVous, [
            'professionnels' => $professionnels,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Le patient connecté est associé automatiquement (non saisi dans le formulaire).
            $rendezVous->setPatient($patient);

            // Statut par défaut pour un nouveau rendez-vous.
            $rendezVous->setStatut('En attente');

            $entityManager->persist($rendezVous);
            $entityManager->flush();

            $this->addFlash('success', 'Votre rendez-vous a bien été enregistré.');

            return $this->redirectToRoute('patient_rendezvous_index');
        }

        return $this->render('patient/rendezvous/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/patient/rendez-vous', name: 'patient_rendezvous_index')]
    public function mesRendezVous(EntityManagerInterface $entityManager): Response
    {
        $patient = $this->getUser();

        if (!$patient instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur patient non reconnu.');
        }

        // On récupère uniquement les rendez-vous du patient connecté.
        $rendezVousList = $entityManager->getRepository(RendezVous::class)->findBy(
            ['patient' => $patient],
            ['dateHeure' => 'ASC']
        );

        return $this->render('patient/rendezvous/index.html.twig', [
            'rendezVousList' => $rendezVousList,
        ]);
    }

    #[Route('/patient/prescriptions', name: 'patient_prescriptions')]
    public function mesPrescriptions(EntityManagerInterface $entityManager): Response
    {
        $patient = $this->getUser();

        if (!$patient instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur patient non reconnu.');
        }

        // Filtrage par patient connecté : on n'affiche que SES prescriptions.
        $prescriptions = $entityManager->getRepository(Prescription::class)->findBy(
            ['patient' => $patient],
            ['datePrescription' => 'DESC']
        );

        return $this->render('patient/prescriptions/index.html.twig', [
            'prescriptions' => $prescriptions,
        ]);
    }

    #[Route('/patient/resultats', name: 'patient_resultats')]
    public function mesResultats(EntityManagerInterface $entityManager): Response
    {
        $patient = $this->getUser();

        if (!$patient instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur patient non reconnu.');
        }

        // Filtrage par patient connecté : on n'affiche que SES résultats d'analyse.
        $resultatsAnalyse = $entityManager->getRepository(ResultatAnalyse::class)->findBy(
            ['patient' => $patient],
            ['dateAnalyse' => 'DESC']
        );

        return $this->render('patient/resultats/index.html.twig', [
            'resultatsAnalyse' => $resultatsAnalyse,
        ]);
    }

    #[Route('/patient/prises-medicaments', name: 'patient_prises_medicaments')]
    public function mesPrisesMedicaments(EntityManagerInterface $entityManager): Response
    {
        $patient = $this->getUser();

        if (!$patient instanceof User) {
            throw $this->createAccessDeniedException('Utilisateur patient non reconnu.');
        }

        // Filtrage par patient connecté : on n'affiche que SES prises de médicaments.
        $prisesMedicaments = $entityManager->getRepository(PriseMedicament::class)->findBy(
            ['patient' => $patient],
            ['id' => 'DESC']
        );

        return $this->render('patient/prises_medicaments/index.html.twig', [
            'prisesMedicaments' => $prisesMedicaments,
        ]);
    }
}
