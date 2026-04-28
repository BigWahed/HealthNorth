# Audit des routes API dans le projet web Health NORTH

Date de l'audit : 28/04/2026  
Projet analysé : application web Symfony (`HealthNorth`)

## 1) Objectif de cet audit
Cet audit sert à préparer une séparation propre entre :
- l'application web Symfony actuelle,
- une future application API indépendante.

Aucune suppression ni modification du code applicatif n'a été faite pendant cet audit.

## 2) Routes API trouvées
Routes récupérées depuis les attributs `#[Route(...)]` et vérifiées avec `php bin/console debug:router`.

### A. Routes qui commencent par `/api/mobile`
| Nom de route | Méthode | URL | Contrôleur::méthode |
|---|---|---|---|
| `api_mobile_patient_dossier` | `GET` | `/api/mobile/patient/{id}/dossier` | `ApiController::mobilePatientDossier` |
| `api_mobile_patient_rendezvous` | `GET` | `/api/mobile/patient/{id}/rendez-vous` | `ApiController::mobilePatientRendezVous` |
| `api_mobile_patient_options` | `GET` | `/api/mobile/patient/{id}/options` | `ApiController::mobilePatientOptions` |
| `api_mobile_patient_alarmes_medicaments` | `GET` | `/api/mobile/patient/{id}/alarmes-medicaments` | `ApiController::mobilePatientAlarmesMedicaments` |

### B. Routes qui commencent par `/api`
| Nom de route | Méthode | URL | Contrôleur::méthode |
|---|---|---|---|
| `api_etablissements` | `GET` | `/api/etablissements` | `ApiController::etablissements` |
| `api_types_intervention` | `GET` | `/api/types-intervention` | `ApiController::typesIntervention` |
| `api_patient_options` | `GET` | `/api/patient/options` | `ApiController::patientOptions` |
| `api_patient_dossier` | `GET` | `/api/patient/dossier` | `ApiController::patientDossier` |
| `api_patient_rendezvous` | `GET` | `/api/patient/rendez-vous` | `ApiController::patientRendezVous` |
| `api_patient_prescriptions` | `GET` | `/api/patient/prescriptions` | `ApiController::patientPrescriptions` |
| `api_patient_alarmes_medicaments` | `GET` | `/api/patient/alarmes-medicaments` | `ApiController::patientAlarmesMedicaments` |
| `api_patient_resultats` | `GET` | `/api/patient/resultats` | `ApiController::patientResultats` |
| `api_login` | `POST` | `/api/login` | `SecurityController::apiLogin` |

## 3) Contrôleurs liés à l'API

### `src/Controller/ApiController.php`
Rôle : contrôleur principal des endpoints API JSON (mobile + patient connecté).

### `src/Controller/SecurityController.php`
Rôle API : contient la route `POST /api/login` (authentification mobile via email/mot de passe).  
Rôle web : contient aussi `/login` et `/logout` pour le site web.

## 4) Fichiers concernés par les routes API

## Fichiers qui semblent **uniquement liés à l'API**
| Fichier | Rôle |
|---|---|
| `src/Controller/ApiController.php` | Gère les réponses JSON des routes `/api` et `/api/mobile`. |

## Fichiers **partagés** (API + web)
Ces fichiers sont utilisés par l'API mais aussi par l'application web. Ils ne sont donc pas "API uniquement".

| Fichier | Rôle |
|---|---|
| `src/Controller/SecurityController.php` | API (`/api/login`) + web (`/login`, `/logout`). |
| `src/Entity/User.php` | Entité utilisateur utilisée partout (API et web). |
| `src/Entity/RendezVous.php` | Données rendez-vous (API et web). |
| `src/Entity/Prescription.php` | Données prescriptions (API et web). |
| `src/Entity/PriseMedicament.php` | Données prises de médicaments (API et web). |
| `src/Entity/ResultatAnalyse.php` | Données résultats d'analyses (API et web). |
| `src/Entity/Etablissement.php` | Données établissements (API et web). |
| `src/Entity/TypeIntervention.php` | Données types d'intervention (API et web). |
| `src/Repository/UserRepository.php` | Utilisé pour la connexion API et la logique web. |
| `config/packages/security.yaml` | Configuration de sécurité globale du projet web actuel. |

## 5) Fichiers à ne surtout pas supprimer (web)
Pour ne pas casser l'application web, il faut conserver :

- tous les contrôleurs web :
  - `src/Controller/AdminController.php`
  - `src/Controller/HomeController.php`
  - `src/Controller/DashboardController.php`
  - `src/Controller/PatientController.php`
  - `src/Controller/ProController.php`
  - `src/Controller/RegistrationController.php`
  - `src/Controller/SecurityController.php` (important : contient aussi le login web)
- tous les templates Twig (`templates/...`) utilisés par les pages web.
- les formulaires Symfony (`src/Form/...`) utilisés dans l'interface web.
- la configuration sécurité/routage/framework (`config/...`) utilisée par le site.
- les entités métier (`src/Entity/...`) tant que la base de données est partagée avec le web.

## 6) Recommandation de migration vers une API dédiée
Recommandation simple en 4 étapes :

1. Créer un nouveau projet Symfony dédié API (nouveau dépôt GitHub).  
2. Copier/migrer d'abord le contrôleur API (`ApiController`) et la partie API du login (`apiLogin`).  
3. Recréer dans ce nouveau projet les entités nécessaires (`User`, `RendezVous`, `Prescription`, etc.) et la configuration sécurité API (authentification token/JWT recommandée).  
4. Dans le projet web actuel, supprimer les routes API uniquement quand la nouvelle API répond exactement pareil (tests Postman/Insomnia avant suppression).

Conseil BTS SIO : migrer endpoint par endpoint (petit à petit), pas tout d'un coup. C'est plus simple à expliquer à l'oral et moins risqué.

## 7) Conclusion
Le projet web contient actuellement **13 routes API** :
- 4 routes `/api/mobile/...`
- 9 routes `/api/...` (dont `/api/login`)

La séparation est faisable proprement, mais il faut bien distinguer les fichiers "API uniquement" des fichiers partagés pour ne pas casser le site web.

## 8) Statut après nettoyage (28/04/2026)
Après migration vers le projet API dédié `health_north_api` :
- `src/Controller/ApiController.php` a été supprimé du projet web.
- La méthode `apiLogin` (route `/api/login`) a été supprimée de `SecurityController`.
- Les routes `/api` et `/api/mobile` n'existent plus dans le projet web.
- Les routes web (`/`, `/login`, `/logout`, dashboards) sont toujours actives.

L'API mobile est maintenant portée uniquement par le projet indépendant :
- `C:\xampp\htdocs\health_north_api`
