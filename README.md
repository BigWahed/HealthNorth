# Health NORTH - Projet Web Symfony

## 1) Objectif du projet web
`HealthNorth` est le projet web Symfony (Twig) pour les utilisateurs navigateur.

Fonctions principales :
- inscription et connexion web
- espaces patient / professionnel / administrateur
- rendez-vous et dossier patient
- gestion admin (etablissements, types d intervention, medicaments)

## 2) Architecture actuelle (separee)
Le backend est separe en 2 projets Symfony :
- Web : `C:\xampp\htdocs\HealthNorth` -> `http://127.0.0.1:8000`
- API : `C:\xampp\htdocs\HealthNorthAPI` -> `http://127.0.0.1:8001`

Les 2 projets utilisent la meme base : `health_north2`.

## 3) Base de donnees
Configuration attendue (`.env`) :

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/health_north2?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
```

## 4) Lancer le projet web
Depuis `C:\xampp\htdocs\HealthNorth` :

```bash
php -S 127.0.0.1:8000 -t public
```

## 5) Verification rapide
Depuis `C:\xampp\htdocs\HealthNorth` :

```bash
php bin/console debug:router
php bin/console lint:container
```

Attendu :
- routes web presentes (`/`, `/login`, `/register`, dashboards)
- pas de routes `/api/*` dans le projet web

## 6) Note importante
L application mobile Flutter ne doit pas appeler `HealthNorth` sur `8000` pour l API.
Elle doit appeler `HealthNorthAPI` sur `8001`.
