# Health NORTH - Setup de base (Symfony + Doctrine)

Ce projet est prepare pour un demarrage simple et pedagogique (niveau BTS SIO SLAM).

## 1) Verification rapide

Lancer:

```bash
php bin/console about
php bin/console list doctrine
```

Si ces 2 commandes fonctionnent, Symfony et Doctrine sont bien installes.

## 2) Configuration de la base de donnees

Le point principal est la variable `DATABASE_URL` dans le fichier `.env`.

Valeur configuree:

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/health_north?serverVersion=10.4.32-MariaDB&charset=utf8mb4"
```

Explication:
- `root` = utilisateur MySQL local (XAMPP classique)
- mot de passe vide apres `root:` (a adapter si besoin)
- `health_north` = nom de la base
- `serverVersion` = version MariaDB (a adapter a ta machine si necessaire)

## 3) Commandes Doctrine a utiliser ensuite

Creer la base:

```bash
php bin/console doctrine:database:create
```

Creer une migration (apres creation/modification des entites):

```bash
php bin/console doctrine:migrations:diff
```

Executer la migration:

```bash
php bin/console doctrine:migrations:migrate
```

## 4) Important pour la suite

Pour cette etape, on ne cree pas encore:
- pages
- authentification
- API


