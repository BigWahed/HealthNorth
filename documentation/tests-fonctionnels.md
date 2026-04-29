# Tests fonctionnels - Health NORTH (Web)

Ce document liste les tests manuels principaux de l application web Symfony Health NORTH.

Statut propose :
- `A tester`
- `OK`
- `KO`

| N° | Fonctionnalite testee | Prerequis | Etapes | Resultat attendu | Statut |
|---|---|---|---|---|---|
| 1 | Inscription patient | Application web lancee | Aller sur `/register`, remplir, valider | Compte cree + redirection `/login` | A tester |
| 2 | Connexion patient | Compte patient existant | Aller sur `/login`, saisir identifiants | Connexion reussie vers espace patient | A tester |
| 3 | Connexion professionnel | Compte pro existant | Aller sur `/login`, saisir identifiants | Connexion reussie vers espace pro | A tester |
| 4 | Connexion administrateur | Compte admin existant | Aller sur `/login`, saisir identifiants | Connexion reussie vers espace admin | A tester |
| 5 | Redirection selon role | Comptes de test dispo | Se connecter avec chaque role | Patient -> `/patient/dashboard`, Pro -> `/pro/dashboard`, Admin -> `/admin/dashboard` | A tester |
| 6 | Acces interdit selon role | Utilisateur connecte | Tester acces aux zones non autorisees | Refus d acces (403/redirection) | A tester |
| 7 | Prise de rendez-vous patient | Patient connecte | Aller sur `/patient/rendez-vous/nouveau`, valider | RDV cree, statut `En attente` | A tester |
| 8 | Liste rendez-vous patient | Patient connecte | Aller sur `/patient/rendez-vous` | Liste du patient connecte uniquement | A tester |
| 9 | Dossier patient enrichi | Patient connecte | Aller sur `/patient/dossier` | Infos patient + sections medicales visibles | A tester |
| 10 | Prescriptions patient | Patient connecte | Aller sur `/patient/prescriptions` | Prescriptions du patient connecte | A tester |
| 11 | Resultats patient | Patient connecte | Aller sur `/patient/resultats` | Resultats du patient connecte | A tester |
| 12 | Prises medicaments patient | Patient connecte | Aller sur `/patient/prises-medicaments` | Donnees du patient connecte | A tester |
| 13 | Liste patients pro | Pro connecte | Aller sur `/pro/patients` | Liste des patients affichée | A tester |
| 14 | Ajout prescription pro | Pro connecte | Creer prescription pour un patient | Prescription enregistree | A tester |
| 15 | Verification cote patient | Prescription creee | Se connecter en patient concerne | Nouvelle prescription visible | A tester |
| 16 | CRUD etablissements admin | Admin connecte | Ajouter/modifier/supprimer | CRUD fonctionnel | A tester |
| 17 | CRUD types intervention admin | Admin connecte | Ajouter/modifier/supprimer | CRUD fonctionnel | A tester |
| 18 | CRUD medicaments admin | Admin connecte | Ajouter/modifier/supprimer | CRUD fonctionnel | A tester |
| 19 | Liste utilisateurs admin | Admin connecte | Aller sur `/admin/utilisateurs` | Liste utilisateurs visible | A tester |

## Note API
Les tests API ne sont plus dans ce document web.
Ils sont a executer sur le projet `HealthNorthAPI` (port `8001`).
