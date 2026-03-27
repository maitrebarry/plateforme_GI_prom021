-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 25 mars 2026 à 19:38
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `plateforme_gi_promo21`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `description`) VALUES
(1, 'Site Web', NULL),
(2, 'Application Web', NULL),
(3, 'Application Mobile', NULL),
(4, 'Application Dekstop', NULL),
(5, 'Web', 'Applications web et plateformes en ligne'),
(6, 'Mobile', 'Applications Android, iOS et hybrides'),
(7, 'Desktop', 'Applications de bureau'),
(8, 'Data / IA', 'Data science, machine learning et intelligence artificielle'),
(9, 'Systèmes embarqués', 'IoT, robots, cartes et dispositifs connectés');

-- --------------------------------------------------------

--
-- Structure de la table `department_posts`
--

CREATE TABLE `department_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `type` enum('annonce','information','resultat','evenement','opportunite') DEFAULT 'annonce',
  `publication_date` date NOT NULL DEFAULT curdate(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `department_posts`
--

INSERT INTO `department_posts` (`id`, `user_id`, `titre`, `contenu`, `type`, `publication_date`, `created_at`) VALUES
(1, 2, 'Résultat du semestre 1', 'ci-joint les resultas du semestre 1 et 2', 'resultat', '2026-03-08', '2026-03-08 17:36:48'),
(2, 2, 'AVIS DE SUSPENSION DES COURS', 'L\'Université de Ségou porte à la connaissance de tous les étudiants et de tout le personnel que les cours seront suspendus à partir du vendredi 10 mars 2026 jusqu\'au lundi 1er mars 2026 à 7h.\r\n\r\nLa reprise des cours est prévue pour le lundi le 01 mars 2026', 'information', '2026-03-08', '2026-03-08 18:03:29'),
(3, 2, 'stage', 'veuillez saisir l\'occasion', 'opportunite', '2026-03-08', '2026-03-08 18:17:23');

-- --------------------------------------------------------

--
-- Structure de la table `department_post_files`
--

CREATE TABLE `department_post_files` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `department_post_files`
--

INSERT INTO `department_post_files` (`id`, `post_id`, `original_name`, `stored_name`, `file_path`, `file_type`, `created_at`) VALUES
(1, 1, 'Documentation-JAGO-DANAYA.doc', 'post_69adb3b08ef6c6.61334623.doc', 'uploads/department_posts/post_69adb3b08ef6c6.61334623.doc', 'doc', '2026-03-08 17:36:48'),
(2, 3, 'ateliko.jpeg', 'post_69adbd33d6ef30.04358663.jpeg', 'uploads/department_posts/post_69adbd33d6ef30.04358663.jpeg', 'jpeg', '2026-03-08 18:17:23'),
(3, 3, 'syneklogo.jpeg', 'post_69adbd33d6f2c1.77473254.jpeg', 'uploads/department_posts/post_69adbd33d6f2c1.77473254.jpeg', 'jpeg', '2026-03-08 18:17:23');

-- --------------------------------------------------------

--
-- Structure de la table `facultes`
--

CREATE TABLE `facultes` (
  `id_faculte` int(11) NOT NULL,
  `universite_id` int(11) NOT NULL,
  `nom_faculte` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `facultes`
--

INSERT INTO `facultes` (`id_faculte`, `universite_id`, `nom_faculte`, `created_at`) VALUES
(1, 1, 'Faculté des Sciences et Techniques (FST)', '2026-03-08 16:30:59'),
(2, 1, 'Faculté de Médecine et d’Odontostomatologie (FMOS)', '2026-03-08 16:30:59'),
(3, 1, 'Faculté de Pharmacie (FAPH)', '2026-03-08 16:30:59'),
(4, 1, 'Institut des Sciences Appliquées (ISA)', '2026-03-08 16:30:59'),
(5, 2, 'Faculté des Sciences Economiques et de Gestion (FSEG)', '2026-03-08 16:30:59'),
(6, 2, 'Faculté d’Histoire et de Géographie (FHG)', '2026-03-08 16:30:59'),
(7, 2, 'Institut Universitaire de Gestion (IUG)', '2026-03-08 16:30:59'),
(8, 3, 'Faculté de Droit Public', '2026-03-08 16:30:59'),
(9, 3, 'Faculté de Droit Privé', '2026-03-08 16:30:59'),
(10, 4, 'Faculté des Lettres, Langues et Sciences du Langage (FLLSL)', '2026-03-08 16:30:59'),
(11, 4, 'Faculté des Sciences Humaines et des Sciences de l’Éducation (FSHSE)', '2026-03-08 16:30:59'),
(12, 4, 'Institut Universitaire de Technologie (IUT)', '2026-03-08 16:30:59'),
(13, 5, 'Faculté des Sciences Sociales', '2026-03-08 16:31:00'),
(14, 5, 'Faculté d’Agronomie et de Médecine Animale', '2026-03-08 16:31:00'),
(15, 5, 'Faculté du Génie et des Sciences', '2026-03-08 16:31:00'),
(16, 5, 'Institut Universitaire de Formation Professionnelle (IUFP)', '2026-03-08 16:31:00');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `technologies` text DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `status` enum('en cours','termine') DEFAULT 'en cours',
  `admin_status` enum('en_attente','valide','rejete') NOT NULL DEFAULT 'en_attente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `projects`
--

INSERT INTO `projects` (`id`, `user_id`, `category_id`, `title`, `description`, `technologies`, `video`, `status`, `admin_status`, `created_at`, `updated_at`) VALUES
(1, 8, 3, 'Atelico', '&amp;lt;p&amp;gt;une application de gestion des attelier de couture &amp;lt;/p&amp;gt;', NULL, 'https://youtu.be/hCrtcVDgCGw?si=HtJ1kmqnuhR4499I', 'en cours', 'en_attente', '2026-03-16 17:20:17', '2026-03-25 16:45:29'),
(2, 8, 2, 'GED', 'Une application de gestion electronique des documents pour les grandes entreprises', NULL, 'https://youtu.be/hCrtcVDgCGw?si=HtJ1kmqnuhR4499I', 'en cours', 'en_attente', '2026-03-16 17:58:44', '2026-03-25 16:45:29'),
(3, 8, 1, 'Hirondelle Market', 'Hirondelle Market est une plateforme de vente en ligne con&ccedil;ue pour faciliter l&rsquo;achat et la vente de produits via Internet. L&rsquo;objectif principal du site est de permettre aux utilisateurs de d&eacute;couvrir une large gamme de produits, de comparer les prix et de commander facilement depuis leur domicile ou leur smartphone.\r\n\r\nLe site propose une interface simple, moderne et intuitive qui permet aux visiteurs de naviguer facilement entre les diff&eacute;rentes cat&eacute;gories de produits. Les utilisateurs peuvent consulter les d&eacute;tails des articles, voir les images des produits, v&eacute;rifier leur disponibilit&eacute; et effectuer leurs achats en quelques clics.\r\n\r\nHirondelle Market vise &eacute;galement &agrave; soutenir les commer&ccedil;ants et entrepreneurs en leur offrant un espace num&eacute;rique pour pr&eacute;senter et vendre leurs produits &agrave; un public plus large. Gr&acirc;ce &agrave; cette plateforme, les vendeurs peuvent g&eacute;rer leurs produits, suivre les commandes et am&eacute;liorer leur visibilit&eacute; en ligne.\r\n\r\nLe syst&egrave;me int&egrave;gre plusieurs fonctionnalit&eacute;s importantes telles que la gestion des comptes utilisateurs, la recherche de produits, la gestion des commandes, ainsi que la s&eacute;curisation des transactions. L&rsquo;objectif est d&rsquo;offrir une exp&eacute;rience d&rsquo;achat fluide, rapide et s&eacute;curis&eacute;e pour tous les utilisateurs.\r\n\r\nEn r&eacute;sum&eacute;, Hirondelle Market repr&eacute;sente une solution moderne de commerce &eacute;lectronique qui rapproche les vendeurs et les acheteurs tout en simplifiant le processus d&rsquo;achat en ligne.', 'JAVA, REACT APP, MYSQL ', 'https://youtu.be/hCrtcVDgCGw?si=Bp-ZDsa4Z4u99AXM', 'en cours', 'en_attente', '2026-03-16 18:17:59', '2026-03-25 16:45:29'),
(4, 8, 3, 'dd', 'ryte', 'PHP, MYSQL ', 'https://youtu.be/u2ah9tWTkmk?si=I8boZXnGy6Lm6ItS', 'en cours', 'en_attente', '2026-03-17 09:31:09', '2026-03-25 16:45:29'),
(5, 8, 1, 'Atelico', '<p>ss</p>', 'PHP, MYSQL ', 'https://youtu.be/hCrtcVDgCGw?si=Bp-ZDsa4Z4u99AXM', 'en cours', 'en_attente', '2026-03-17 09:59:12', '2026-03-25 16:45:29'),
(6, 8, 2, 'list', 'la vie', 'JAVA, REACT APP, MYSQL ', 'https://youtu.be/u2ah9tWTkmk?si=I8boZXnGy6Lm6ItS', 'en cours', 'en_attente', '2026-03-17 10:19:00', '2026-03-25 16:45:29'),
(7, 8, 2, 'fqj', 'sfq', 'PHP, MYSQL ', 'https://youtu.be/hCrtcVDgCGw?si=Bp-ZDsa4Z4u99AXM', 'en cours', 'en_attente', '2026-03-17 10:19:41', '2026-03-25 16:45:29'),
(8, 8, 4, 'fsqjf', 'sfqfs', 'PHP, MYSQL ', 'https://youtu.be/u2ah9tWTkmk?si=I8boZXnGy6Lm6ItS', 'en cours', 'en_attente', '2026-03-17 10:20:23', '2026-03-25 16:45:29'),
(9, 9, 2, 'OumouKon&amp;amp;eacute; Beauty Manager (OKBM)', '<p>OumouKoné Beauty Manager (OKBM) est une application web et/ou mobile dédiée à la gestion intelligente des salons de coiffure.\r\n\r\nElle permet aux propriétaires de salons de :\r\n\r\ngérer leurs clients\r\norganiser les rendez-vous\r\nsuivre leurs revenus\r\ngérer les coiffeuses\r\naméliorer leur visibilité\r\n\r\n👉 Le projet vise à digitaliser les salons de coiffure au Mali et en Afrique, où beaucoup fonctionnent encore de manière manuelle.</p>', 'PHP,JAVA ', 'https://pin.it/6Dt3b7lQV', 'en cours', 'en_attente', '2026-03-25 16:13:01', '2026-03-25 16:45:29');

-- --------------------------------------------------------

--
-- Structure de la table `project_files`
--

CREATE TABLE `project_files` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `fichier` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `project_files`
--

INSERT INTO `project_files` (`id`, `project_id`, `fichier`) VALUES
(1, 1, 'file_69b83bd1380b1.txt'),
(2, 1, 'file_69b83bd13bf0c.zip'),
(3, 2, 'file_69b844d4c8d11.zip'),
(4, 3, 'file_69b84957abd0d.zip'),
(5, 4, 'file_69b91f5db8606.zip'),
(6, 6, 'file_69b92a94736cb.zip'),
(7, 8, 'file_69b92ae749c5e.zip');

-- --------------------------------------------------------

--
-- Structure de la table `project_images`
--

CREATE TABLE `project_images` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `project_images`
--

INSERT INTO `project_images` (`id`, `project_id`, `image`) VALUES
(2, 1, 'img_69b83bd125da8.png'),
(3, 1, 'img_69b83bd129a07.png'),
(4, 1, 'img_69b83bd134398.png'),
(5, 2, 'img_69b844d4ad3f5.png'),
(6, 2, 'img_69b844d4b6f32.png'),
(7, 2, 'img_69b844d4c0fb0.png'),
(9, 3, 'img_69b84957983f1.jpg'),
(10, 3, 'img_69b849579cdff.png'),
(11, 3, 'img_69b84957a7683.png'),
(12, 4, 'img_69b91f5dac42c.jpg'),
(14, 5, '69b92a14bc0a1.png'),
(15, 5, '69b92a14bfe1f.png'),
(16, 5, '69b92a14c3cac.png'),
(17, 6, 'img_69b92a9461a02.png'),
(18, 6, 'img_69b92a946ae66.png'),
(19, 6, 'img_69b92a946f0da.png'),
(20, 7, 'img_69b92abd28296.png'),
(21, 8, 'img_69b92ae71f8c4.png'),
(22, 8, 'img_69b92ae729300.png'),
(23, 8, 'img_69b92ae72cd6e.png'),
(24, 8, 'img_69b92ae7365c5.png'),
(25, 8, 'img_69b92ae73a57b.png'),
(26, 8, 'img_69b92ae73e1c1.png'),
(27, 8, 'img_69b92ae741c87.png'),
(28, 8, 'img_69b92ae745911.png'),
(29, 9, 'img_69c4098d51185.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `project_likes`
--

CREATE TABLE `project_likes` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `project_likes`
--

INSERT INTO `project_likes` (`id`, `project_id`, `user_id`, `created_at`) VALUES
(1, 8, 9, '2026-03-25 16:50:20'),
(2, 9, 9, '2026-03-25 16:50:53'),
(3, 6, 9, '2026-03-25 18:23:30');

-- --------------------------------------------------------

--
-- Structure de la table `project_messages`
--

CREATE TABLE `project_messages` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `project_messages`
--

INSERT INTO `project_messages` (`id`, `project_id`, `sender_id`, `receiver_id`, `message`, `is_read`, `created_at`) VALUES
(1, 8, 9, 8, 'bonjour monsion', 0, '2026-03-25 17:44:48');

-- --------------------------------------------------------

--
-- Structure de la table `project_reviews`
--

CREATE TABLE `project_reviews` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Déchargement des données de la table `project_reviews`
--

INSERT INTO `project_reviews` (`id`, `project_id`, `user_id`, `rating`, `review`, `created_at`, `updated_at`) VALUES
(1, 9, 9, 5, 'c\'est un bon projet', '2026-03-25 16:52:14', '2026-03-25 16:52:14'),
(2, 6, 9, 5, '', '2026-03-25 18:23:50', '2026-03-25 18:23:50');

-- --------------------------------------------------------

--
-- Structure de la table `universites`
--

CREATE TABLE `universites` (
  `id_universite` int(11) NOT NULL,
  `nom_universite` varchar(255) NOT NULL,
  `type_etablissement` enum('universite_publique','grande_ecole','etablissement_prive','autre') NOT NULL DEFAULT 'universite_publique',
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `universites`
--

INSERT INTO `universites` (`id_universite`, `nom_universite`, `type_etablissement`, `actif`, `created_at`) VALUES
(1, 'Université des Sciences, des Techniques et des Technologies de Bamako (USTTB)', 'universite_publique', 1, '2026-03-08 16:30:59'),
(2, 'Université des Sciences Sociales et de Gestion de Bamako (USSGB)', 'universite_publique', 1, '2026-03-08 16:30:59'),
(3, 'Université des Sciences Juridiques et Politiques de Bamako (USJPB)', 'universite_publique', 1, '2026-03-08 16:30:59'),
(4, 'Université des Lettres et des Sciences Humaines de Bamako (ULSHB)', 'universite_publique', 1, '2026-03-08 16:30:59'),
(5, 'Université de Ségou', 'universite_publique', 1, '2026-03-08 16:30:59'),
(6, 'Centre de Recherche et de Formation pour l’Industrie Textile (CERFITEX)', 'grande_ecole', 1, '2026-03-08 16:30:59'),
(7, 'Ecole Nationale d’Ingénieurs / Abderhamane Baba Touré (ENI-ABT)', 'grande_ecole', 1, '2026-03-08 16:30:59'),
(8, 'Ecole Normale d’Enseignement Technique et Professionnel (ENETP)', 'grande_ecole', 1, '2026-03-08 16:30:59'),
(9, 'Ecole Normale Supérieure (ENSUP)', 'grande_ecole', 1, '2026-03-08 16:30:59'),
(10, 'Ecole Supérieure de Journalisme et des Sciences de la Communication (ESJSC)', 'grande_ecole', 1, '2026-03-08 16:30:59'),
(11, 'Institut de Pédagogie Universitaire (IPU)', 'grande_ecole', 1, '2026-03-08 16:30:59'),
(12, 'Institut National de Formation des Travailleurs Sociaux (INFTS)', 'grande_ecole', 1, '2026-03-08 16:30:59'),
(13, 'Institut National de Formation en Sciences de la Santé (INFSS)', 'grande_ecole', 1, '2026-03-08 16:30:59'),
(14, 'Institut National de la Jeunesse et des Sports (INJS)', 'grande_ecole', 1, '2026-03-08 16:30:59'),
(15, 'Institut Polytechnique Rural de Formation et de Recherche Appliquée (IPR/IFRA)', 'grande_ecole', 1, '2026-03-08 16:30:59'),
(16, 'Institut Zayed des Sciences Economiques et Juridiques (IZSEJ)', 'grande_ecole', 1, '2026-03-08 16:30:59');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contact` varchar(30) DEFAULT NULL,
  `universite` varchar(255) DEFAULT NULL,
  `faculte` varchar(255) DEFAULT NULL,
  `filiere` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `role` enum('etudiant','admin','der') DEFAULT 'etudiant',
  `statut_compte` enum('actif','bloque') DEFAULT 'actif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `universite_id` int(11) DEFAULT NULL,
  `faculte_id` int(11) DEFAULT NULL,
  `autre_etablissement` varchar(255) DEFAULT NULL,
  `autre_departement` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `nom`, `prenom`, `email`, `contact`, `universite`, `faculte`, `filiere`, `password`, `image`, `github`, `linkedin`, `role`, `statut_compte`, `created_at`, `universite_id`, `faculte_id`, `autre_etablissement`, `autre_departement`) VALUES
(1, 'Moustapha', 'Barry', 'barrymoustapha485@gmail.com', '', 'Administration', NULL, NULL, '$2y$10$pFnHkEaLDkiV1Tm9dDSkLOnpKa86RswXnQF8G09BBcy7bbuoH/jJS', NULL, NULL, NULL, 'admin', 'actif', '2026-03-08 16:31:13', NULL, NULL, NULL, NULL),
(2, 'WAIGALO', 'Amadou dit Amobo', 'waigalo@gmail.com', '76543212', 'Université de Ségou', 'Institut Universitaire de Formation Professionnelle (IUFP)', NULL, '$2y$10$jrBrmB60sNnsjMiiJvmcoedIMecfZquR4P3f6mZn7VhHd1m8ksiQi', 'profil_69adc0da98be35.69567076.jpg', NULL, NULL, 'der', 'actif', '2026-03-08 16:48:49', 5, 16, NULL, NULL),
(3, 'KAREMBE', 'Amadou', 'amadoukarembesira@gmail.com', '71234567', 'Université de Ségou', 'Institut Universitaire de Formation Professionnelle (IUFP)', NULL, '$2y$10$TQrXttl6kQX14/GDTWwVqOFOcA99rzftUYyh.0OgQCfp52yhI4i4O', NULL, NULL, NULL, 'admin', 'actif', '2026-03-08 21:16:52', 5, 16, NULL, NULL),
(4, 'TANGARA', 'Fatoumata  Zarahou', 'tangarazarahou@gmail.com', '81234567', 'Université de Ségou', 'Institut Universitaire de Formation Professionnelle (IUFP)', NULL, '$2y$10$ocselWmF8KlArwepQbcVreeDMOYwqsPNGdYJyhGlEatiuw4IbLfmq', NULL, NULL, NULL, 'admin', 'actif', '2026-03-08 21:17:58', 5, 16, NULL, NULL),
(5, 'OULOGUEM', 'Oumar', 'oumarouolo2023@gmail.com', '90564321', 'Université de Ségou', 'Institut Universitaire de Formation Professionnelle (IUFP)', NULL, '$2y$10$D3SmaBJO6Nzdj992jmCOEehDLx0tbOkF2BHoam1Oh/XLRtvu9o6P6', NULL, NULL, NULL, 'admin', 'actif', '2026-03-08 21:19:25', 5, 16, NULL, NULL),
(6, 'KONE', 'Abdoulaye', 'kkabdoulaye514@gmail.com', '91234567', 'Université de Ségou', 'Institut Universitaire de Formation Professionnelle (IUFP)', NULL, '$2y$10$aQVf5EzBgpt966aXX6QxHOC3I65QBQThYORSko721p7NGXLTlJjZC', NULL, NULL, NULL, 'admin', 'actif', '2026-03-08 21:20:41', 5, 16, NULL, NULL),
(7, 'SIDIBE', 'Kabine', 'sidibe@gmail.com', NULL, 'Université de Ségou', 'Institut Universitaire de Formation Professionnelle (IUFP)', 'GI', '$2y$10$8aILW.W/d5uxSVLURBeQA.yNITpfTW57wNgksuP3rvqmQ40knm4KG', NULL, NULL, NULL, 'etudiant', 'actif', '2026-03-08 21:42:11', 5, 16, NULL, NULL),
(8, 'KATA', 'KOULOU', 'issa@gmail.con', NULL, 'Université de Ségou', 'Faculté des Sciences Sociales', 'Informqtique', '$2y$10$dH1G7uSfI4LeN.Jbi96LtO0O.zq/nlGM4eTTeOaU.XEbjdQc5f3eG', NULL, NULL, NULL, 'etudiant', 'actif', '2026-03-16 11:03:17', 5, 13, NULL, NULL),
(9, 'Dirra', 'Oumou', 'oumou@gmail.com', NULL, 'Université de Ségou', 'Institut Universitaire de Formation Professionnelle (IUFP)', 'Genie Informatique', '$2y$10$Ta6cJJBBRNUtH/wOSX2GKuHbHbbcLB.syhXN1cNu2ackL8Xtm.LE.', NULL, NULL, NULL, 'etudiant', 'actif', '2026-03-25 16:04:36', 5, 16, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `department_posts`
--
ALTER TABLE `department_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_department_posts_user` (`user_id`);

--
-- Index pour la table `department_post_files`
--
ALTER TABLE `department_post_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_department_post_files_post` (`post_id`);

--
-- Index pour la table `facultes`
--
ALTER TABLE `facultes`
  ADD PRIMARY KEY (`id_faculte`),
  ADD UNIQUE KEY `uq_faculte_par_universite` (`universite_id`,`nom_faculte`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Index pour la table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `idx_projects_admin_status` (`admin_status`);

--
-- Index pour la table `project_files`
--
ALTER TABLE `project_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Index pour la table `project_images`
--
ALTER TABLE `project_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Index pour la table `project_likes`
--
ALTER TABLE `project_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_project_like` (`project_id`,`user_id`),
  ADD KEY `fk_project_likes_user` (`user_id`);

--
-- Index pour la table `project_messages`
--
ALTER TABLE `project_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_project_messages_project` (`project_id`),
  ADD KEY `fk_project_messages_sender` (`sender_id`),
  ADD KEY `fk_project_messages_receiver` (`receiver_id`);

--
-- Index pour la table `project_reviews`
--
ALTER TABLE `project_reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_project_review` (`project_id`,`user_id`),
  ADD KEY `fk_project_reviews_user` (`user_id`);

--
-- Index pour la table `universites`
--
ALTER TABLE `universites`
  ADD PRIMARY KEY (`id_universite`),
  ADD UNIQUE KEY `nom_universite` (`nom_universite`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_universite` (`universite_id`),
  ADD KEY `fk_users_faculte` (`faculte_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `department_posts`
--
ALTER TABLE `department_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `department_post_files`
--
ALTER TABLE `department_post_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `facultes`
--
ALTER TABLE `facultes`
  MODIFY `id_faculte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `project_files`
--
ALTER TABLE `project_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `project_images`
--
ALTER TABLE `project_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `project_likes`
--
ALTER TABLE `project_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `project_messages`
--
ALTER TABLE `project_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `project_reviews`
--
ALTER TABLE `project_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `universites`
--
ALTER TABLE `universites`
  MODIFY `id_universite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `department_posts`
--
ALTER TABLE `department_posts`
  ADD CONSTRAINT `fk_department_posts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `department_post_files`
--
ALTER TABLE `department_post_files`
  ADD CONSTRAINT `fk_department_post_files_post` FOREIGN KEY (`post_id`) REFERENCES `department_posts` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `facultes`
--
ALTER TABLE `facultes`
  ADD CONSTRAINT `fk_facultes_universites` FOREIGN KEY (`universite_id`) REFERENCES `universites` (`id_universite`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `project_files`
--
ALTER TABLE `project_files`
  ADD CONSTRAINT `project_files_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `project_images`
--
ALTER TABLE `project_images`
  ADD CONSTRAINT `project_images_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `project_likes`
--
ALTER TABLE `project_likes`
  ADD CONSTRAINT `fk_project_likes_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_project_likes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `project_messages`
--
ALTER TABLE `project_messages`
  ADD CONSTRAINT `fk_project_messages_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_project_messages_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_project_messages_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `project_reviews`
--
ALTER TABLE `project_reviews`
  ADD CONSTRAINT `fk_project_reviews_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_project_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_faculte` FOREIGN KEY (`faculte_id`) REFERENCES `facultes` (`id_faculte`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_users_universite` FOREIGN KEY (`universite_id`) REFERENCES `universites` (`id_universite`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
