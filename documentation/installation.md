# Installation - Health NORTH (Projet Web)

## 1) Presentation
`HealthNorth` est l application web Symfony (Twig).
L API mobile est dans un projet separe : `HealthNorthAPI`.

## 2) Prerequis
- PHP
- Composer
- MySQL/MariaDB
- phpMyAdmin (optionnel)

## 3) Installation web
Depuis `C:\xampp\htdocs\HealthNorth` :

```bash
composer install
```

## 4) Configuration base web
Fichier `.env` :

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/health_north2?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
```

## 5) Commandes Doctrine
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load --no-interaction
```

## 6) Lancer le web
```bash
php -S 127.0.0.1:8000 -t public
```

## 7) Comptes de test
- `admin@healthnorth.fr / Password123!`
- `medecin@healthnorth.fr / Password123!`
- `patient1@healthnorth.fr / Password123!`
- `patient2@healthnorth.fr / Password123!`

## 8) Routes web principales
- `/`
- `/register`
- `/login`
- `/patient/dashboard`
- `/pro/dashboard`
- `/admin/dashboard`

## 9) Separation web/API
Le projet web `HealthNorth` ne sert plus les routes `/api/*`.
Les routes API sont servies par `HealthNorthAPI` sur le port `8001`.

## 10) Lancer les deux projets
Terminal 1 (web) :

```bash
cd C:\xampp\htdocs\HealthNorth
php -S 127.0.0.1:8000 -t public
```

Terminal 2 (api) :

```bash
cd C:\xampp\htdocs\HealthNorthAPI
php -S 127.0.0.1:8001 -t public
```
