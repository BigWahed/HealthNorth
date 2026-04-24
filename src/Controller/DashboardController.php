<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function adminDashboard(): Response
    {
        return $this->render('dashboard/dashboard.html.twig', [
            'page_title' => 'Dashboard Admin',
            'page_subtitle' => 'Vue de pilotage globale de la plateforme.',
            'role_label' => 'ROLE_ADMIN',
            'cards' => [
                [
                    'title' => 'Utilisateurs',
                    'text' => 'Consulter les comptes et verifier les roles.',
                    'action' => 'Voir les utilisateurs',
                ],
                [
                    'title' => 'Etablissements',
                    'text' => 'Verifier les cliniques, laboratoires et centres.',
                    'action' => 'Voir les etablissements',
                ],
                [
                    'title' => 'Suivi technique',
                    'text' => 'Controler le bon fonctionnement de la plateforme.',
                    'action' => 'Voir le suivi',
                ],
            ],
        ]);
    }

    #[Route('/pro/dashboard', name: 'pro_dashboard')]
    public function proDashboard(): Response
    {
        return $this->render('dashboard/dashboard.html.twig', [
            'page_title' => 'Dashboard Professionnel',
            'page_subtitle' => 'Espace du medecin pour organiser les soins.',
            'role_label' => 'ROLE_PRO',
            'cards' => [
                [
                    'title' => 'Rendez-vous',
                    'text' => 'Visualiser les rendez-vous planifies.',
                    'action' => 'Voir les rendez-vous',
                ],
                [
                    'title' => 'Prescriptions',
                    'text' => 'Consulter et preparer les ordonnances.',
                    'action' => 'Voir les prescriptions',
                ],
                [
                    'title' => 'Resultats d analyse',
                    'text' => 'Suivre les examens et leurs statuts.',
                    'action' => 'Voir les resultats',
                ],
            ],
        ]);
    }

    #[Route('/patient/dashboard', name: 'patient_dashboard')]
    public function patientDashboard(): Response
    {
        // Dashboard patient: cartes simples et pedagogiques pour les futures pages metier.
        return $this->render('dashboard/dashboard.html.twig', [
            'page_title' => 'Dashboard Patient',
            'page_subtitle' => 'Espace personnel de suivi médical.',
            'role_label' => 'ROLE_PATIENT',
            'cards' => [
                [
                    'title' => 'Prendre un rendez-vous',
                    'text' => 'Réserver une consultation, une analyse ou une intervention.',
                    'action' => 'Prendre rendez-vous',
                    'link' => '/patient/rendez-vous/nouveau',
                ],
                [
                    'title' => 'Mes rendez-vous',
                    'text' => 'Consulter mes rendez-vous programmés.',
                    'action' => 'Voir mes rendez-vous',
                    'link' => '/patient/rendez-vous',
                ],
                [
                    'title' => 'Mon dossier patient',
                    'text' => 'Retrouver mes informations médicales principales.',
                    'action' => 'Voir mon dossier',
                    'link' => '/patient/dossier',
                ],
                [
                    'title' => 'Mes prescriptions',
                    'text' => 'Consulter les prescriptions ajoutées par les professionnels de santé.',
                    'action' => 'Voir mes prescriptions',
                    'link' => '/patient/prescriptions',
                ],
                [
                    'title' => 'Mes prises de médicaments',
                    'text' => 'Suivre l’ordonnancement de mes prises de médicaments.',
                    'action' => 'Voir mes prises',
                    'link' => '/patient/prises-medicaments',
                ],
                [
                    'title' => 'Mes résultats d’analyse',
                    'text' => 'Retrouver mes derniers résultats médicaux.',
                    'action' => 'Voir mes resultats',
                    'link' => '/patient/resultats',
                ],
            ],
        ]);
    }
}
