# Historique architecture API - Health NORTH

Date de mise a jour : 29/04/2026

## Etat actuel (a retenir)
L architecture active est :
- `HealthNorth` = projet web Symfony (port `8000`)
- `HealthNorthAPI` = projet API Symfony separe (port `8001`)
- base commune = `health_north2`

## Note
Ce document remplace les anciennes notes qui indiquaient une API integree au projet web.
Ces anciennes notes ne sont plus valides.

## Routes API actives (dans HealthNorthAPI)
- `POST /api/login`
- `GET /api/etablissements`
- `GET /api/types-intervention`
- `GET /api/mobile/patient/{id}/dossier`
- `GET /api/mobile/patient/{id}/rendez-vous`
- `GET /api/mobile/patient/{id}/options`
- `GET /api/mobile/patient/{id}/alarmes-medicaments`
