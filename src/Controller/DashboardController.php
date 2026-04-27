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
            'page_title' => 'Espace administrateur',
            'page_subtitle' => 'Vue de pilotage globale de la plateforme.',
            'role_label' => 'Administration',
            'cards' => [
                [
                    'title' => 'Utilisateurs',
                    'text' => 'Consulter les comptes et vérifier les rôles.',
                    'action' => 'Voir les utilisateurs',
                    'link' => '/admin/utilisateurs',
                ],
                [
                    'title' => 'Établissements',
                    'text' => 'Vérifier les cliniques, laboratoires et centres.',
                    'action' => 'Voir les établissements',
                    'link' => '/admin/etablissements',
                ],
                [
                    'title' => 'Types d’intervention',
                    'text' => 'Gérer les types d’intervention proposés.',
                    'action' => 'Voir les types',
                    'link' => '/admin/types-intervention',
                ],
                [
                    'title' => 'Médicaments',
                    'text' => 'Gérer la liste des médicaments.',
                    'action' => 'Voir les médicaments',
                    'link' => '/admin/medicaments',
                ],
            ],
        ]);
    }

    #[Route('/pro/dashboard', name: 'pro_dashboard')]
    public function proDashboard(): Response
    {
        return $this->render('dashboard/dashboard.html.twig', [
            'page_title' => 'Espace professionnel',
            'page_subtitle' => 'Espace du médecin pour organiser les soins.',
            'role_label' => 'Professionnel de sante',
            'cards' => [
                [
                    'title' => 'Rendez-vous',
                    'text' => 'Visualiser les rendez-vous planifiés.',
                    'action' => 'Voir les rendez-vous',
                    'link' => '/pro/rendez-vous',
                ],
                [
                    'title' => 'Patients',
                    'text' => 'Consulter la liste des patients et accéder au dossier.',
                    'action' => 'Voir les patients',
                    'link' => '/pro/patients',
                ],
                [
                    'title' => 'Ajouter une prescription',
                    'text' => 'Créer rapidement une prescription pour un patient.',
                    'action' => 'Nouvelle prescription',
                    'link' => '/pro/prescription/nouvelle',
                ],
                [
                    'title' => 'Résultats d’analyse',
                    'text' => 'Suivre les examens et leurs statuts.',
                    'action' => 'Voir les résultats',
                    'link' => '/pro/resultats',
                ],
            ],
        ]);
    }

    #[Route('/patient/dashboard', name: 'patient_dashboard')]
    public function patientDashboard(): Response
    {
        // Espace patient : cartes simples et pédagogiques pour les futures pages métier.
        return $this->render('dashboard/dashboard.html.twig', [
            'page_title' => 'Espace patient',
            'page_subtitle' => 'Espace personnel de suivi médical.',
            'role_label' => 'Suivi personnel',
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
                    'action' => 'Voir mes résultats',
                    'link' => '/patient/resultats',
                ],
            ],
        ]);
    }
}
