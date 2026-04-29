# Documentation API - Health NORTH API

Date : 29/04/2026
Projet : `C:\xampp\htdocs\HealthNorthAPI`

## 1) Objectif
Cette API Symfony separee expose des routes JSON pour l application mobile Flutter.

Base URL :
- Flutter Web : `http://127.0.0.1:8001`
- Android Emulator : `http://10.0.2.2:8001`

## 2) Base de donnees
API et Web utilisent la meme base MySQL : `health_north2`.

`HealthNorthAPI/.env` :

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/health_north2?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
```

## 3) Endpoints disponibles

### `POST /api/login`
Body JSON :

```json
{
  "email": "patient1@healthnorth.fr",
  "password": "Password123!"
}
```

Succes (`200`) :

```json
{
  "success": true,
  "user": {
    "id": 15,
    "email": "patient1@healthnorth.fr",
    "nom": "Dupont",
    "prenom": "Alice",
    "roles": ["ROLE_PATIENT"]
  }
}
```

Erreur (`401`) :

```json
{
  "success": false,
  "message": "Identifiants incorrects"
}
```

### `GET /api/etablissements`
Retour : `{ "success": true, "etablissements": [...] }`

### `GET /api/types-intervention`
Retour : `{ "success": true, "types": [...] }`

### `GET /api/mobile/patient/{id}/dossier`
Retour : `{ "success": true, "patient": {...}, "prescriptions": [...], "resultatsAnalyses": [...] }`

### `GET /api/mobile/patient/{id}/rendez-vous`
Retour : `{ "success": true, "rendezVous": [...] }`

### `GET /api/mobile/patient/{id}/options`
Retour : `{ "success": true, "options": [...] }`

### `GET /api/mobile/patient/{id}/alarmes-medicaments`
Retour : `{ "success": true, "alarmes": [...] }`

## 4) Lancer l API
Depuis `C:\xampp\htdocs\HealthNorthAPI` :

```bash
php -S 127.0.0.1:8001 -t public
```

## 5) Verifications
Depuis `C:\xampp\htdocs\HealthNorthAPI` :

```bash
php bin/console debug:router
php bin/console lint:container
```

Tests HTTP minimaux :
- `GET http://127.0.0.1:8001/api/etablissements`
- `GET http://127.0.0.1:8001/api/types-intervention`
- `POST http://127.0.0.1:8001/api/login`

## 6) Architecture finale
- Web Symfony : `HealthNorth` (`8000`)
- API Symfony : `HealthNorthAPI` (`8001`)
- Mobile Flutter : `HealhNorthMobile`
- Base commune : `health_north2`
