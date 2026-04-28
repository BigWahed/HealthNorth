# Architecture API finale - Health NORTH

Date : 28/04/2026  
Projet : `C:\xampp\htdocs\HealthNorth`

## 1) Choix d'architecture retenu
L'API est maintenant integree directement dans le projet web Symfony `HealthNorth`.

Le projet `C:\\xampp\\htdocs\\health_north_api` a ete supprime definitivement le 28/04/2026.

## 2) Routes API actives dans le projet web
- `POST /api/login`
- `GET /api/etablissements`
- `GET /api/types-intervention`
- `GET /api/mobile/patient/{id}/dossier`
- `GET /api/mobile/patient/{id}/rendez-vous`
- `GET /api/mobile/patient/{id}/options`
- `GET /api/mobile/patient/{id}/alarmes-medicaments`

## 3) Organisation retenue (simple BTS)
- pages web Symfony classiques (Twig) conservees
- API JSON dans des controleurs dedies :
  - `src/Controller/AuthApiController.php`
  - `src/Controller/PublicApiController.php`
  - `src/Controller/MobilePatientApiController.php`
- meme base de donnees : `health_north2`

## 4) Verification rapide
Commande :

```bash
php bin/console debug:router | Select-String -Pattern "api_|/api"
```

Resultat attendu : les 7 routes API ci-dessus sont presentes.

## 5) Conclusion
Architecture finale:
- application mobile Flutter -> routes `/api` du projet web `HealthNorth`
- navigateur web -> pages Symfony classiques
- une seule application backend a maintenir, plus simple a expliquer a l'oral BTS.

