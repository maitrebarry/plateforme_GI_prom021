# Guide de déploiement — NGAKODON / PLATEFORME_GI_PROMO021

Vitrine des projets étudiants en Génie Informatique (Promo 2021). Application
PHP MVC « maison » (pas de framework, pas de Composer). L'application est
aussi une **PWA** (installable, fonctionne hors ligne).

Ce document décrit comment la mettre en ligne sur un hébergement mutualisé
type **LWS** (ou tout autre hébergeur Apache + PHP + MySQL/MariaDB).

---

## 1. Prérequis

| Composant | Version recommandée |
|-----------|---------------------|
| PHP | **8.0+** |
| Extensions PHP | `pdo_mysql`, `mbstring`, `fileinfo`, `curl`, `json` |
| Base de données | MySQL 5.7+ **ou** MariaDB 10.4+ |
| Serveur web | Apache avec `mod_rewrite` (idéalement `mod_headers`, `mod_expires`, `mod_mime`) |

> ℹ️ Pas de Composer / `vendor/` à installer : l'application n'a aucune
> dépendance externe (PDO natif, cURL natif pour l'assistant IA).

---

## 2. Architecture (à connaître avant de déployer)

```
plateforme_GI_prom021/
├── index.php               ← point d'entrée UNIQUE de l'application
├── .htaccess                ← bloque l'accès direct à app/, docs/, backups/, fichiers cachés
├── app/
│   ├── core/config.php      ← NE PAS MODIFIER (lit env + config.local.php)
│   ├── core/config.local.php        ← À CRÉER sur l'hébergeur (secrets, NON versionné)
│   ├── core/config.local.php.sample ← modèle à copier
│   ├── controller/ models/ services/ views/
├── public/                  ← assets (CSS/JS/images), PWA, .htaccess de routage
│   ├── .htaccess             ← réécrit tout vers ../index.php (portable, aucun chemin en dur)
│   ├── manifest.webmanifest, sw.js, offline.html   ← PWA
│   ├── assets/               ← CSS, JS, images, icônes PWA
│   ├── image_profile/        ← uploads (photos de profil)
│   └── uploads/department_posts/  ← uploads (posts département)
├── uploads/projects/{images,files}/  ← uploads (projets étudiants)
├── docs/                    ← scripts de migration SQL (structure uniquement)
└── backups/                 ← dumps SQL locaux (jamais versionnés)
```

⚠️ **Important — spécifique à ce projet** : contrairement à d'autres apps où
l'on pointe le dossier racine du site directement sur `/public`, **ici il ne
faut PAS faire ça**. Le front controller `index.php` vit à la racine du
projet (pas dans `public/`), et `public/.htaccess` renvoie vers
`../index.php`. Le dossier racine du site doit donc pointer sur le dossier du
**projet lui-même** (celui qui contient `index.php`). L'application ajoute
ensuite automatiquement `/public` dans toutes ses URL internes (routes,
assets) — c'est normal et volontaire.

Le routage se fait via `?url=Controleur/methode` ; `public/.htaccess`
réécrit toutes les URL sous `/public/` vers `../index.php`. **L'URL de base
est détectée automatiquement** (schéma http/https, sous-dossier).

---

## 3. Déploiement pas à pas (LWS)

### Étape 1 — Copier les fichiers
Transférez tout le dossier `plateforme_GI_prom021/` sur l'espace LWS (FTP,
Git, ou le gestionnaire de fichiers de l'espace client).

### Étape 2 — Configurer le dossier racine du domaine/sous-domaine

| Scénario | Accès | À configurer chez LWS |
|----------|-------|------------------------|
| **A. Domaine/sous-domaine dédié** | `https://promo021.mon-domaine.com/` | Dans « Gérer le site » → pointer le dossier racine du domaine/sous-domaine sur `.../plateforme_GI_prom021/` (le dossier qui contient `index.php`, **pas** `public/`) |
| **B. Sous-dossier** (dossier racine non modifiable) | `https://mon-domaine.com/plateforme_GI_prom021/` | Rien de spécial : déposez le dossier tel quel dans `www/` ou `public_html/`, ça fonctionne directement |

Dans les deux cas, la page d'accueil est aussi accessible telle quelle
(`.../plateforme_GI_prom021/` ou `https://promo021.mon-domaine.com/`) ; tous
les liens internes de l'app naviguent ensuite sous `/public/...`.

### Étape 3 — Créer la configuration locale (secrets BDD)
Copiez `app/core/config.local.php.sample` en **`app/core/config.local.php`**
(jamais versionné) et renseignez :

```php
<?php
$dbHost = "localhost";
$dbName = "moncompte_plateforme_gi";
$dbUser = "moncompte_user";
$dbPass = "MOT_DE_PASSE_BDD";

// Optionnel : assistant IA (voir §6)
// $hfApiToken = "hf_xxxxxxxx";
```

> **Alternative** : définissez plutôt les variables d'environnement
> `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD` si votre hébergeur le
> permet (LWS le permet via `.htaccess` `SetEnv` ou la configuration PHP du
> compte).

### Étape 4 — Transférer la base de données
1. **Exporter** depuis le poste local (XAMPP) — phpMyAdmin → *Exporter*, ou :
   ```bash
   mysqldump -u root plateforme_gi_promo21 > plateforme_gi_promo21.sql
   ```
2. **Créer** une base vide dans l'espace client LWS (section « Bases de
   données MySQL »).
3. **Importer** le fichier via phpMyAdmin (LWS) ou :
   ```bash
   mysql -u UTILISATEUR -p NOM_BASE < plateforme_gi_promo21.sql
   ```
4. Le script `docs/migration_compatibilite_admin_status.sql` (structure
   uniquement, sans données) doit être rejoué **si votre dump ne contient
   pas déjà la colonne `projects.admin_status`**.

### Étape 5 — Droits d'écriture sur les dossiers d'upload
```
public/image_profile/
public/uploads/department_posts/
uploads/projects/images/
uploads/projects/files/
```
(en SSH : `chmod -R 775` ; sur le gestionnaire de fichiers LWS : 755/775).

### Étape 6 — Activer HTTPS
Activez le certificat SSL gratuit (Let's Encrypt) depuis l'espace client
LWS. **HTTPS est requis pour l'installation de la PWA** (sauf en
`localhost`).

---

## 4. Vérification post-déploiement

1. Ouvrir `https://VOTRE-URL/` → la page d'accueil (vitrine des projets)
   s'affiche, styles présents.
2. Se connecter / créer un compte → tableau de bord accessible.
3. Tester un upload (photo de profil, projet) → pas d'erreur de droits.
4. Console du navigateur (F12) : aucune erreur 404 sur les assets
   (`/public/assets/...`).
5. **PWA** : bouton *Installer* dans la barre d'adresse (Edge/Chrome).
   DevTools → *Application* → *Service Workers* → **activated** ; *Manifest*
   liste bien le nom NGAKODON et les icônes.

---

## 5. Sécurité (important)

- ✅ `app/core/config.local.php` est **exclu de Git** (`.gitignore`).
- ✅ `.htaccess` (racine) interdit l'accès direct à `app/`, `docs/`,
  `backups/` et aux fichiers cachés (`.git`, `.env`…), ainsi qu'aux fichiers
  `.sql`, `.md`, `.sample`, `.bak`, `.log`, `.ini`, `.sh`, `.lock`.
- 🔑 **À FAIRE** : un dump SQL (`backups/projects_backup_*.sql`) avait été
  commité par erreur dans Git (déjà poussé sur GitHub). Il a été retiré du
  suivi (`git rm --cached`) et le dossier `backups/` est maintenant ignoré,
  mais il **reste dans l'historique Git**. Il ne contenait que la table
  `projects` (pas de mots de passe), le risque est donc limité — mais si ce
  dépôt est public, envisagez de purger l'historique (`git filter-repo` /
  BFG) ou de rendre le dépôt privé.
- Les erreurs PHP ne sont pas masquées explicitement dans ce projet (pas de
  flag debug dédié) : pensez à désactiver `display_errors` dans la
  configuration PHP du compte LWS en production (`php.ini` du compte ou
  panneau « Configuration PHP »).

---

## 6. Dépannage rapide

| Symptôme | Cause probable / solution |
|----------|---------------------------|
| **Erreur de connexion à la base de données** | Vérifier `app/core/config.local.php` (hôte/nom/user/mot de passe). |
| **Page blanche / erreur 500** | Consulter le log d'erreurs Apache/PHP du compte LWS. Souvent : `mod_rewrite` désactivé, ou permissions sur les dossiers d'upload. |
| **Liens en 404 après connexion** | `mod_rewrite` non actif, ou dossier racine du site pointé par erreur sur `public/` au lieu du dossier projet (voir §2). |
| **404 persistants malgré un dossier racine correct** | Ouvrir `public/.htaccess` et décommenter la ligne `RewriteBase` correspondant à l'URL réelle de `public/`. |
| **PWA non installable** | HTTPS manquant, ou `manifest.webmanifest` non servi avec le type `application/manifest+json`. |
| **Upload (photo/projet) en échec** | Droits d'écriture sur les 4 dossiers listés au §3/étape 5. |

---

## 7. Checklist finale

- [ ] Dossier racine du domaine pointé sur le dossier **projet** (pas `public/`)
- [ ] `app/core/config.local.php` créé avec les identifiants BDD
- [ ] Base de données importée (+ migration `admin_status` si besoin)
- [ ] Dossiers d'upload accessibles en écriture
- [ ] HTTPS actif
- [ ] Connexion + tableau de bord OK, upload testé
- [ ] Service worker *activated* + bouton *Installer* présent
- [ ] 🔑 Décision prise sur le dump SQL historique (dépôt privé ou purge d'historique)
