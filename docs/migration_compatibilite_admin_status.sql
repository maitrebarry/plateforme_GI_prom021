-- Migration de compatibilite entre la base et le workflow actuel de l'application
-- Date: 2026-03-27
-- Objectif:
-- 1. conserver `projects.status` pour l'avancement metier
-- 2. ajouter `projects.admin_status` pour la moderation administrateur
-- 3. normaliser les valeurs existantes
-- Usage:
-- Executer ce script une seule fois sur la base cible.

START TRANSACTION;

ALTER TABLE `projects`
  ADD COLUMN `admin_status` ENUM('en_attente','valide','rejete') NOT NULL DEFAULT 'en_attente' AFTER `status`;

-- On derive d'abord la moderation admin a partir des valeurs deja presentes.
UPDATE `projects`
SET `admin_status` = CASE
    WHEN LOWER(TRIM(COALESCE(`status`, ''))) IN ('termine', 'valide', 'publie', 'publiee', 'accepte', 'acceptee') THEN 'valide'
    WHEN LOWER(TRIM(COALESCE(`status`, ''))) IN ('rejete', 'refuse', 'refusee') THEN 'rejete'
    ELSE 'en_attente'
END;

-- Ensuite on ramene `status` a son role metier uniquement.
ALTER TABLE `projects`
  MODIFY COLUMN `status` ENUM('en cours','termine') NOT NULL DEFAULT 'en cours';

UPDATE `projects`
SET `status` = CASE
    WHEN `admin_status` = 'valide' THEN 'termine'
    ELSE 'en cours'
END;

ALTER TABLE `projects`
  ADD INDEX `idx_projects_admin_status` (`admin_status`);

COMMIT;

-- Verification recommandee apres execution:
-- SELECT id, title, status, admin_status FROM projects ORDER BY created_at DESC;
