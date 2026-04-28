# Documentation API - Health NORTH

Date : 28/04/2026  
Projet : `C:\xampp\htdocs\HealthNorth`

## 1) Objectif
Cette API permet a l'application mobile Flutter d'acceder aux donnees medicales utiles en JSON.

Base URL :
- Android Emulator : `http://10.0.2.2:8000`
- Flutter Web : `http://127.0.0.1:8000`

## 2) Format general
- Toutes les routes renvoient du JSON.
- Aucune page HTML n'est retournee pour ces endpoints.

## 3) Endpoints disponibles

## 3.1 Authentification
### `POST /api/login`
Connexion utilisateur avec email + mot de passe.

Exemple body JSON :
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
    "id": 1,
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

## 3.2 Donnees publiques
### `GET /api/etablissements`
Retourne la liste des etablissements.

Champs : `id`, `nom`, `type`, `adresse`, `ville`, `codePostal`

### `GET /api/types-intervention`
Retourne la liste des types d'intervention.

Champs : `id`, `libelle`, `description`

## 3.3 Donnees mobile patient
### `GET /api/mobile/patient/{id}/dossier`
Retourne le dossier du patient.

Champs :
`id`, `nom`, `prenom`, `email`, `telephone`, `adresse`, `dateNaissance`, `photo`,
`numeroSecuriteSociale`, `personneContact`, `telephonePersonneContact`, `medecinTraitant`

Si patient introuvable (`404`) :
```json
{
  "success": false,
  "message": "Patient introuvable"
}
```

### `GET /api/mobile/patient/{id}/rendez-vous`
Retourne les rendez-vous du patient.

Champs : `id`, `dateHeure`, `statut`, `etablissement`, `typeIntervention`, `professionnel`

### `GET /api/mobile/patient/{id}/options`
Retourne des options simples pour la V1 BTS.

Exemple :
```json
[
  {
    "id": 1,
    "libelle": "Rappel avant rendez-vous",
    "description": "Recevoir un rappel avant un rendez-vous medical",
    "statut": "Actif"
  },
  {
    "id": 2,
    "libelle": "Contact d'urgence",
    "description": "Personne a prevenir en cas de besoin",
    "statut": "Actif"
  }
]
```

### `GET /api/mobile/patient/{id}/alarmes-medicaments`
Retourne les prises de medicaments du patient.

Champs : `medicament`, `posologie`, `frequence`, `momentPrise`

## 4) Codes HTTP utilises
- `200` : succes
- `401` : identifiants incorrects
- `404` : ressource introuvable (ex: patient)

## 5) Test rapide (Postman / Insomnia)
1. Tester `POST /api/login`
2. Recuperer un `id` patient
3. Tester les routes `/api/mobile/patient/{id}/...`
4. Verifier que les reponses sont bien en JSON

## 6) Architecture finale (BTS)
- Backend unique : `HealthNorth`
- API integree dans `HealthNorth` (routes `/api`)
- Mobile Flutter consomme cette API
- Base de donnees partagee : `health_north2`
