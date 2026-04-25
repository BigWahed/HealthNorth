# Installation - Health NORTH

## 1) Présentation rapide du projet
Health NORTH est une application Symfony de suivi médical (projet pédagogique BTS SIO SLAM).  
Elle permet:
- l'inscription et la connexion des utilisateurs,
- un espace patient,
- un espace professionnel de santé,
- un espace administrateur,
- une petite API REST pour préparer une future application mobile.

## 2) Technologies utilisées
- PHP
- Symfony
- Doctrine ORM
- MySQL / MariaDB
- Twig
- Bootstrap

## 3) Prérequis
Installer sur votre machine:
- PHP (version compatible Symfony)
- Composer
- Symfony CLI (ou un serveur PHP local)
- MySQL ou MariaDB
- phpMyAdmin (recommandé pour visualiser la base)

## 4) Installation du projet
Dans le dossier du projet:

```bash
composer install
```

## 5) Configuration de la base de données
La configuration se fait dans le fichier `.env` avec `DATABASE_URL`.

Exemple utilisé dans ce projet:

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/health_north2?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
```

Adaptez si besoin:
- utilisateur MySQL
- mot de passe MySQL
- port
- nom de la base

## 6) Création de la base
```bash
php bin/console doctrine:database:create
```

## 7) Exécution des migrations
```bash
php bin/console doctrine:migrations:migrate
```

## 8) Chargement des fixtures
```bash
php bin/console doctrine:fixtures:load --no-interaction
```

## 9) Lancement du serveur local
Option 1 (Symfony CLI):
```bash
symfony server:start
```

Option 2 (serveur PHP):
```bash
php -S 127.0.0.1:8000 -t public
```

## 10) Comptes de test
- admin@healthnorth.fr / Password123!
- medecin@healthnorth.fr / Password123!
- patient1@healthnorth.fr / Password123!
- patient2@healthnorth.fr / Password123!

## 11) Routes principales
- `/` : landing page
- `/register` : inscription patient
- `/login` : connexion
- `/logout` : déconnexion
- `/patient/dashboard` : tableau de bord patient
- `/pro/dashboard` : tableau de bord professionnel
- `/admin/dashboard` : tableau de bord administrateur
- `/patient/rendez-vous` : rendez-vous patient
- `/pro/patients` : liste des patients (pro)
- `/admin/etablissements` : gestion admin des établissements

## 12) Routes API principales
- `GET /api/etablissements`
- `GET /api/types-intervention`
- `GET /api/patient/rendez-vous`
- `GET /api/patient/prescriptions`
- `GET /api/patient/resultats`

## 13) Explication simple de l'architecture MVC
- **Model**: les entités Doctrine (`src/Entity`) + base de données.  
- **View**: les pages Twig (`templates`).  
- **Controller**: la logique applicative (`src/Controller`) qui relie les données et les vues.

En résumé: le contrôleur reçoit la requête, récupère les données (Model), puis affiche une vue Twig (View).

## 14) Rôle de l'API (explication simple)
L'API permet d'exposer certaines données en JSON (sans page HTML).  
Elle sert surtout à:
- connecter plus tard une application mobile,
- permettre à d'autres clients (Postman, front JS, mobile) d'utiliser les données,
- séparer la partie interface web et la partie échange de données.
