# Nettoyage des anciennes routes API dans le projet web Health NORTH

Date : 28/04/2026  
Projet : `C:\xampp\htdocs\HealthNorth`

## 1) Pourquoi ce nettoyage
L'API a été séparée dans un projet indépendant :
- `C:\xampp\htdocs\health_north_api`

Le projet web n'a donc plus besoin de conserver ses anciennes routes API (`/api` et `/api/mobile`).

Objectif : éviter les doublons et garder un projet web propre, sans casser les pages web.

## 2) Ce qui a été supprimé

### Fichier supprimé
- `src/Controller/ApiController.php`

Raison : ce contrôleur était uniquement lié à l'ancienne API intégrée.

### Code supprimé dans un fichier partagé
- Méthode `apiLogin()` (route `POST /api/login`) supprimée de `src/Controller/SecurityController.php`

Raison : le login API est maintenant géré dans le projet `health_north_api`.

## 3) Ce qui a été conservé (important)
Pour ne pas casser l'application web, ces éléments ont été conservés :
- toutes les entités (`src/Entity/...`)
- les repositories (`src/Repository/...`)
- les templates Twig (`templates/...`)
- les formulaires (`src/Form/...`)
- les contrôleurs web (`AdminController`, `HomeController`, `DashboardController`, `PatientController`, `ProController`, `RegistrationController`)
- `SecurityController` pour les routes web `/login` et `/logout`

## 4) Vérifications effectuées

### A. Vérification des routes API
Commande :

```bash
php bin/console debug:router | Select-String -Pattern "api_|/api"
```

Résultat : aucune route API trouvée dans le projet web.

### B. Vérification des routes web principales
Commande :

```bash
php bin/console debug:router | Select-String -Pattern "app_login|app_logout|app_home|admin_dashboard|pro_dashboard|patient_dashboard"
```

Résultat : routes web présentes.

### C. Vérification du conteneur Symfony
Commande :

```bash
php bin/console lint:container
```

Résultat : OK.

## 5) Conclusion
Le nettoyage est terminé :
- l'ancienne API intégrée a été retirée du projet web,
- les fonctionnalités web sont conservées,
- l'API est désormais centralisée dans le projet dédié `health_north_api`.
