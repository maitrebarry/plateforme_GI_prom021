CREATE DATABASE IF NOT EXISTS plateforme_gi_promo21 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE plateforme_gi_promo21;

CREATE TABLE IF NOT EXISTS universites (
    id_universite INT AUTO_INCREMENT PRIMARY KEY,
    nom_universite VARCHAR(255) NOT NULL UNIQUE,
    type_etablissement ENUM('universite_publique','grande_ecole','etablissement_prive','autre') NOT NULL DEFAULT 'universite_publique',
    actif TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS facultes (
    id_faculte INT AUTO_INCREMENT PRIMARY KEY,
    universite_id INT NOT NULL,
    nom_faculte VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_facultes_universites
        FOREIGN KEY (universite_id) REFERENCES universites(id_universite)
        ON DELETE CASCADE,
    UNIQUE KEY uq_faculte_par_universite (universite_id, nom_faculte)
);

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    contact VARCHAR(30) NULL,
    universite VARCHAR(255) NULL,
    faculte VARCHAR(255) NULL,
    filiere VARCHAR(255) NULL,
    password VARCHAR(255) NOT NULL,
    image VARCHAR(255) NULL,
    github VARCHAR(255) NULL,
    linkedin VARCHAR(255) NULL,
    role ENUM('etudiant','admin','der') DEFAULT 'etudiant',
    statut_compte ENUM('actif','bloque') DEFAULT 'actif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    universite_id INT NULL,
    faculte_id INT NULL,
    autre_etablissement VARCHAR(255) NULL,
    autre_departement VARCHAR(255) NULL,
    CONSTRAINT fk_users_universite
        FOREIGN KEY (universite_id) REFERENCES universites(id_universite)
        ON DELETE SET NULL,
    CONSTRAINT fk_users_faculte
        FOREIGN KEY (faculte_id) REFERENCES facultes(id_faculte)
        ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS department_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    type ENUM('annonce','information','resultat','evenement','opportunite') DEFAULT 'annonce',
    publication_date DATE NOT NULL DEFAULT (CURRENT_DATE),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_department_posts_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS department_post_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_department_post_files_post
        FOREIGN KEY (post_id) REFERENCES department_posts(id)
        ON DELETE CASCADE
);

-- Migration pour base existante
ALTER TABLE users
    ADD COLUMN IF NOT EXISTS faculte VARCHAR(255) NULL AFTER universite,
    ADD COLUMN IF NOT EXISTS faculte_id INT NULL AFTER universite_id,
    ADD COLUMN IF NOT EXISTS autre_etablissement VARCHAR(255) NULL AFTER faculte_id,
    ADD COLUMN IF NOT EXISTS autre_departement VARCHAR(255) NULL AFTER autre_etablissement,
    ADD COLUMN IF NOT EXISTS statut_compte ENUM('actif','bloque') DEFAULT 'actif' AFTER role,
    MODIFY COLUMN filiere VARCHAR(255) NULL;

ALTER TABLE users
    DROP COLUMN IF EXISTS filiere_id;

ALTER TABLE department_posts
    ADD COLUMN IF NOT EXISTS publication_date DATE NOT NULL DEFAULT (CURRENT_DATE) AFTER type;

CREATE TABLE IF NOT EXISTS department_post_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(50) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_department_post_files_post
        FOREIGN KEY (post_id) REFERENCES department_posts(id)
        ON DELETE CASCADE
);

-- Seed universités
INSERT IGNORE INTO universites (nom_universite, type_etablissement) VALUES
('Université des Sciences, des Techniques et des Technologies de Bamako (USTTB)', 'universite_publique'),
('Université des Sciences Sociales et de Gestion de Bamako (USSGB)', 'universite_publique'),
('Université des Sciences Juridiques et Politiques de Bamako (USJPB)', 'universite_publique'),
('Université des Lettres et des Sciences Humaines de Bamako (ULSHB)', 'universite_publique'),
('Université de Ségou', 'universite_publique'),
('Centre de Recherche et de Formation pour l’Industrie Textile (CERFITEX)', 'grande_ecole'),
('Ecole Nationale d’Ingénieurs / Abderhamane Baba Touré (ENI-ABT)', 'grande_ecole'),
('Ecole Normale d’Enseignement Technique et Professionnel (ENETP)', 'grande_ecole'),
('Ecole Normale Supérieure (ENSUP)', 'grande_ecole'),
('Ecole Supérieure de Journalisme et des Sciences de la Communication (ESJSC)', 'grande_ecole'),
('Institut de Pédagogie Universitaire (IPU)', 'grande_ecole'),
('Institut National de Formation des Travailleurs Sociaux (INFTS)', 'grande_ecole'),
('Institut National de Formation en Sciences de la Santé (INFSS)', 'grande_ecole'),
('Institut National de la Jeunesse et des Sports (INJS)', 'grande_ecole'),
('Institut Polytechnique Rural de Formation et de Recherche Appliquée (IPR/IFRA)', 'grande_ecole'),
('Institut Zayed des Sciences Economiques et Juridiques (IZSEJ)', 'grande_ecole');

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Faculté des Sciences et Techniques (FST)'
FROM universites WHERE nom_universite LIKE 'Université des Sciences, des Techniques%';

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Faculté de Médecine et d’Odontostomatologie (FMOS)'
FROM universites WHERE nom_universite LIKE 'Université des Sciences, des Techniques%';

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Faculté de Pharmacie (FAPH)'
FROM universites WHERE nom_universite LIKE 'Université des Sciences, des Techniques%';

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Institut des Sciences Appliquées (ISA)'
FROM universites WHERE nom_universite LIKE 'Université des Sciences, des Techniques%';

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Faculté des Sciences Economiques et de Gestion (FSEG)'
FROM universites WHERE nom_universite LIKE 'Université des Sciences Sociales%';

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Faculté d’Histoire et de Géographie (FHG)'
FROM universites WHERE nom_universite LIKE 'Université des Sciences Sociales%';

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Institut Universitaire de Gestion (IUG)'
FROM universites WHERE nom_universite LIKE 'Université des Sciences Sociales%';

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Faculté de Droit Public'
FROM universites WHERE nom_universite LIKE 'Université des Sciences Juridiques%';

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Faculté de Droit Privé'
FROM universites WHERE nom_universite LIKE 'Université des Sciences Juridiques%';

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Faculté des Lettres, Langues et Sciences du Langage (FLLSL)'
FROM universites WHERE nom_universite LIKE 'Université des Lettres et des Sciences Humaines%';

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Faculté des Sciences Humaines et des Sciences de l’Éducation (FSHSE)'
FROM universites WHERE nom_universite LIKE 'Université des Lettres et des Sciences Humaines%';

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Institut Universitaire de Technologie (IUT)'
FROM universites WHERE nom_universite LIKE 'Université des Lettres et des Sciences Humaines%';

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Faculté des Sciences Sociales'
FROM universites WHERE nom_universite = 'Université de Ségou';

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Faculté d’Agronomie et de Médecine Animale'
FROM universites WHERE nom_universite = 'Université de Ségou';

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Faculté du Génie et des Sciences'
FROM universites WHERE nom_universite = 'Université de Ségou';

INSERT IGNORE INTO facultes (universite_id, nom_faculte)
SELECT id_universite, 'Institut Universitaire de Formation Professionnelle (IUFP)'
FROM universites WHERE nom_universite = 'Université de Ségou';
