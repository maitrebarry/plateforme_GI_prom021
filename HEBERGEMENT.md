# Hebergement PLATEFORME_GI_PROMO021 (NGAKODON)

La configuration principale se fait dans `app/core/config.php` (a ne pas
modifier en production) et, pour les secrets, dans `app/core/config.local.php`
(a creer, non versionne — voir `config.local.php.sample`).

## 1. Base de donnees

Deux facons de renseigner les identifiants, par ordre de priorite :

1. **Fichier local** `app/core/config.local.php` (recommande sur un
   hebergement mutualise type LWS) :

   ```php
   <?php
   $dbHost = "localhost";
   $dbName = "moncompte_plateforme_gi";
   $dbUser = "moncompte_user";
   $dbPass = "MOT_DE_PASSE_BDD";
   ```

2. **Variables d'environnement** : `DB_HOST`, `DB_NAME`, `DB_USER`,
   `DB_PASSWORD`.

Sans rien configurer, l'app utilise les valeurs par defaut XAMPP
(`localhost` / `plateforme_gi_promo21` / `root` / mot de passe vide).

## 2. URL de l'application

Rien a configurer : l'URL de base est **detectee automatiquement**
(`ROOT_IMG` + `/public`) a partir de l'hote et du chemin du script. Voir
DEPLOIEMENT.md pour le choix du dossier racine du site chez l'hebergeur.

## 3. Dossiers d'upload

Ces dossiers doivent etre **accessibles en ecriture** par le serveur web :

```
public/image_profile/
public/uploads/department_posts/
uploads/projects/images/
uploads/projects/files/
```

## 4. Assistant IA (optionnel) et connexion sociale (optionnelle)

Definissables via `config.local.php` (`$hfApiToken`, `$hfModel`) ou variables
d'environnement `HF_API_TOKEN`, `HF_MODEL`, `GOOGLE_CLIENT_ID` /
`GOOGLE_CLIENT_SECRET`, `FACEBOOK_CLIENT_ID` / `FACEBOOK_CLIENT_SECRET`,
`GITHUB_CLIENT_ID` / `GITHUB_CLIENT_SECRET`. Tant qu'ils sont vides, les
fonctionnalites correspondantes restent simplement inactives (le reste de
l'app fonctionne normalement).
