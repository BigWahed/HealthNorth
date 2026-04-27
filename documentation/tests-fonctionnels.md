# Tests fonctionnels - Health NORTH

Ce document liste les tests manuels principaux de l'application Symfony Health NORTH.

Statut proposé :
- `À tester`
- `OK`
- `KO`

| N° | Fonctionnalité testée | Prérequis | Étapes | Résultat attendu | Statut |
|---|---|---|---|---|---|
| 1 | Inscription patient | Application lancée, utilisateur non connecté | 1. Aller sur `/register` 2. Remplir nom, prénom, email, mot de passe, date de naissance, téléphone, adresse 3. Valider | Le compte est créé avec rôle `ROLE_PATIENT`, message de succès, redirection vers `/login` | À tester |
| 2 | Connexion patient | Compte patient existant (ex: `patient1@healthnorth.fr`) | 1. Aller sur `/login` 2. Saisir email + mot de passe 3. Valider | Connexion réussie, accès à l’espace patient | À tester |
| 3 | Connexion professionnel | Compte pro existant (`medecin@healthnorth.fr`) | 1. Aller sur `/login` 2. Saisir identifiants pro 3. Valider | Connexion réussie, accès à l’espace professionnel | À tester |
| 4 | Connexion administrateur | Compte admin existant (`admin@healthnorth.fr`) | 1. Aller sur `/login` 2. Saisir identifiants admin 3. Valider | Connexion réussie, accès à l’espace administrateur | À tester |
| 5 | Redirection selon le rôle | Comptes de test disponibles | 1. Se connecter avec un patient 2. Se déconnecter 3. Se connecter avec pro 4. Se déconnecter 5. Se connecter avec admin | Patient -> `/patient/dashboard`, Pro -> `/pro/dashboard`, Admin -> `/admin/dashboard` | À tester |
| 6 | Accès interdit selon le rôle | Utilisateur connecté avec un rôle donné | 1. Connecté en patient, essayer `/admin/dashboard` et `/pro/dashboard` 2. Connecté en pro, essayer `/admin/dashboard` et `/patient/dashboard` | Refus d'accès (403 ou redirection sécurité), pas d'accès aux zones non autorisées | À tester |
| 7 | Prise de rendez-vous patient | Patient connecté, établissements + types + pro existants | 1. Aller sur `/patient/rendez-vous/nouveau` 2. Remplir formulaire 3. Valider | Rendez-vous créé, patient associé automatiquement, statut `En attente`, message flash succès | À tester |
| 8 | Consultation des rendez-vous patient | Patient connecté, rendez-vous existants | 1. Aller sur `/patient/rendez-vous` | Liste des rendez-vous du patient connecté uniquement | À tester |
| 9 | Consultation du dossier patient (version enrichie) | Patient connecté | 1. Aller sur `/patient/dossier` | Le dossier affiche les infos perso classiques + `photo`, `numeroSecuriteSociale`, `personneContact`, `telephonePersonneContact`, `medecinTraitant`, ainsi que les résumés (rdv, prescriptions, résultats, prises) du patient connecté uniquement | À tester |
| 10 | Consultation des prescriptions patient | Patient connecté, prescriptions existantes | 1. Aller sur `/patient/prescriptions` | Tableau des prescriptions du patient connecté uniquement | À tester |
| 11 | Consultation des résultats d'analyse patient | Patient connecté, résultats existants | 1. Aller sur `/patient/resultats` | Tableau des résultats du patient connecté uniquement | À tester |
| 12 | Consultation des prises de médicaments patient | Patient connecté, prises existantes | 1. Aller sur `/patient/prises-medicaments` | Tableau des prises du patient connecté uniquement | À tester |
| 13 | Liste des patients côté professionnel | Professionnel connecté | 1. Aller sur `/pro/patients` | Liste des utilisateurs ayant `ROLE_PATIENT` avec bouton voir dossier | À tester |
| 14 | Ajout d'une prescription par professionnel | Professionnel connecté, patient existant | 1. Aller sur `/pro/prescription/nouvelle` (ou `/pro/patient/{id}/prescription/nouvelle`) 2. Remplir et valider | Prescription enregistrée, pro connecté associé automatiquement, redirection dossier patient, flash succès | À tester |
| 15 | Vérification de la prescription côté patient | Prescription créée par pro pour un patient | 1. Se connecter en patient concerné 2. Aller sur `/patient/prescriptions` | La nouvelle prescription est visible dans la liste du patient concerné | À tester |
| 16 | CRUD établissement côté administrateur | Admin connecté | 1. Aller sur `/admin/etablissements` 2. Ajouter un établissement 3. Modifier 4. Supprimer | Les opérations CRUD fonctionnent et les changements sont visibles en liste | À tester |
| 17 | CRUD type d'intervention côté administrateur | Admin connecté | 1. Aller sur `/admin/types-intervention` 2. Ajouter 3. Modifier 4. Supprimer | Les opérations CRUD fonctionnent et les changements sont visibles en liste | À tester |
| 18 | CRUD médicament côté administrateur | Admin connecté | 1. Aller sur `/admin/medicaments` 2. Ajouter 3. Modifier 4. Supprimer | Les opérations CRUD fonctionnent et les changements sont visibles en liste | À tester |
| 19 | Consultation des utilisateurs côté administrateur | Admin connecté | 1. Aller sur `/admin/utilisateurs` | Liste des utilisateurs affichée (nom, prénom, email, rôles, téléphone) | À tester |
| 20 | API `/api/etablissements` | Application lancée, établissements existants | 1. Appeler `GET /api/etablissements` (navigateur, Postman ou curl) | Réponse JSON avec la liste des établissements (id, nom, type, adresse, ville, codePostal) | À tester |
| 21 | API `/api/patient/resultats` | Patient connecté avec résultats existants | 1. Se connecter en patient 2. Appeler `GET /api/patient/resultats` | Réponse JSON avec uniquement les résultats du patient connecté | À tester |
| 22 | API `/api/patient/dossier` | Patient connecté | 1. Se connecter en patient 2. Appeler `GET /api/patient/dossier` | Réponse JSON avec : `id`, `nom`, `prenom`, `email`, `telephone`, `adresse`, `dateNaissance`, `photo`, `numeroSecuriteSociale`, `personneContact`, `telephonePersonneContact`, `medecinTraitant` | À tester |

## Notes de validation

- Lancer les tests dans un ordre logique : authentification, espaces métier, puis API.
- En cas d'échec, noter le message d'erreur exact et la route concernée.
- Mettre à jour la colonne `Statut` après chaque vérification.
- Vérifier que les données du dossier patient sont cohérentes entre la page web et l'API, car elles seront partagées avec la future application Flutter.

