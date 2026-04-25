# Tests fonctionnels - Health NORTH

Ce document liste les tests manuels principaux de l'application Symfony Health NORTH.

Statut propose:
- `A tester`
- `OK`
- `KO`

| N° | Fonctionnalite testee | Prerequis | Etapes | Resultat attendu | Statut |
|---|---|---|---|---|---|
| 1 | Inscription patient | Application lancee, utilisateur non connecte | 1. Aller sur `/register` 2. Remplir nom, prenom, email, mot de passe, date de naissance, telephone, adresse 3. Valider | Le compte est cree avec role `ROLE_PATIENT`, message de succes, redirection vers `/login` | A tester |
| 2 | Connexion patient | Compte patient existant (ex: `patient1@healthnorth.fr`) | 1. Aller sur `/login` 2. Saisir email + mot de passe 3. Valider | Connexion reussie, acces au dashboard patient | A tester |
| 3 | Connexion professionnel | Compte pro existant (`medecin@healthnorth.fr`) | 1. Aller sur `/login` 2. Saisir identifiants pro 3. Valider | Connexion reussie, acces au dashboard professionnel | A tester |
| 4 | Connexion administrateur | Compte admin existant (`admin@healthnorth.fr`) | 1. Aller sur `/login` 2. Saisir identifiants admin 3. Valider | Connexion reussie, acces au dashboard admin | A tester |
| 5 | Redirection selon le role | Comptes de test disponibles | 1. Se connecter avec un patient 2. Se deconnecter 3. Se connecter avec pro 4. Se deconnecter 5. Se connecter avec admin | Patient -> `/patient/dashboard`, Pro -> `/pro/dashboard`, Admin -> `/admin/dashboard` | A tester |
| 6 | Acces interdit selon le role | Utilisateur connecte avec un role donne | 1. Connecte en patient, essayer `/admin/dashboard` et `/pro/dashboard` 2. Connecte en pro, essayer `/admin/dashboard` et `/patient/dashboard` | Refus d'acces (403 ou redirection securite), pas d'acces aux zones non autorisees | A tester |
| 7 | Prise de rendez-vous patient | Patient connecte, etablissements + types + pro existants | 1. Aller sur `/patient/rendez-vous/nouveau` 2. Remplir formulaire 3. Valider | Rendez-vous cree, patient associe automatiquement, statut `En attente`, message flash succes | A tester |
| 8 | Consultation des rendez-vous patient | Patient connecte, rendez-vous existants | 1. Aller sur `/patient/rendez-vous` | Liste des rendez-vous du patient connecte uniquement | A tester |
| 9 | Consultation du dossier patient | Patient connecte | 1. Aller sur `/patient/dossier` | Le dossier affiche infos perso + resumés (rdv, prescriptions, resultats, prises) du patient connecte uniquement | A tester |
| 10 | Consultation des prescriptions patient | Patient connecte, prescriptions existantes | 1. Aller sur `/patient/prescriptions` | Tableau des prescriptions du patient connecte uniquement | A tester |
| 11 | Consultation des resultats d'analyse patient | Patient connecte, resultats existants | 1. Aller sur `/patient/resultats` | Tableau des resultats du patient connecte uniquement | A tester |
| 12 | Consultation des prises de medicaments patient | Patient connecte, prises existantes | 1. Aller sur `/patient/prises-medicaments` | Tableau des prises du patient connecte uniquement | A tester |
| 13 | Liste des patients cote professionnel | Professionnel connecte | 1. Aller sur `/pro/patients` | Liste des utilisateurs ayant `ROLE_PATIENT` avec bouton voir dossier | A tester |
| 14 | Ajout d'une prescription par professionnel | Professionnel connecte, patient existant | 1. Aller sur `/pro/prescription/nouvelle` (ou `/pro/patient/{id}/prescription/nouvelle`) 2. Remplir et valider | Prescription enregistree, pro connecte associe automatiquement, redirection dossier patient, flash succes | A tester |
| 15 | Verification de la prescription cote patient | Prescription creee par pro pour un patient | 1. Se connecter en patient concerne 2. Aller sur `/patient/prescriptions` | La nouvelle prescription est visible dans la liste du patient concerne | A tester |
| 16 | CRUD etablissement cote administrateur | Admin connecte | 1. Aller sur `/admin/etablissements` 2. Ajouter un etablissement 3. Modifier 4. Supprimer | Les operations CRUD fonctionnent et les changements sont visibles en liste | A tester |
| 17 | CRUD type d'intervention cote administrateur | Admin connecte | 1. Aller sur `/admin/types-intervention` 2. Ajouter 3. Modifier 4. Supprimer | Les operations CRUD fonctionnent et les changements sont visibles en liste | A tester |
| 18 | CRUD medicament cote administrateur | Admin connecte | 1. Aller sur `/admin/medicaments` 2. Ajouter 3. Modifier 4. Supprimer | Les operations CRUD fonctionnent et les changements sont visibles en liste | A tester |
| 19 | Consultation des utilisateurs cote administrateur | Admin connecte | 1. Aller sur `/admin/utilisateurs` | Liste des utilisateurs affichee (nom, prenom, email, roles, telephone) | A tester |
| 20 | API `/api/etablissements` | Application lancee, etablissements existants | 1. Appeler `GET /api/etablissements` (navigateur, Postman ou curl) | Reponse JSON avec la liste des etablissements (id, nom, type, adresse, ville, codePostal) | A tester |
| 21 | API `/api/patient/resultats` | Patient connecte avec resultats existants | 1. Se connecter en patient 2. Appeler `GET /api/patient/resultats` | Reponse JSON avec uniquement les resultats du patient connecte | A tester |

## Notes de validation

- Lancer les tests dans un ordre logique: authentification, espaces metier, puis API.
- En cas d'echec, noter le message d'erreur exact et la route concernee.
- Mettre a jour la colonne `Statut` apres chaque verification.
