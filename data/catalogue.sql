-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : ven. 28 juin 2024 à 08:36
-- Version du serveur : 8.0.37
-- Version de PHP : 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `catalogue`
--

-- --------------------------------------------------------

--
-- Structure de la table `carts`
--

CREATE TABLE `carts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commentaries`
--

CREATE TABLE `commentaries` (
  `id` int NOT NULL,
  `commentary_user_id` int NOT NULL,
  `commentary_product_id` int NOT NULL,
  `commentary_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `conversations`
--

CREATE TABLE `conversations` (
  `id` int NOT NULL,
  `type` varchar(255) NOT NULL,
  `id_from` int NOT NULL,
  `id_to` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `conversations`
--

INSERT INTO `conversations` (`id`, `type`, `id_from`, `id_to`) VALUES
(1, 'conversation', 4, 3);

-- --------------------------------------------------------

--
-- Structure de la table `groups`
--

CREATE TABLE `groups` (
  `groupe_id` int NOT NULL,
  `group_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `groups`
--

INSERT INTO `groups` (`groupe_id`, `group_name`) VALUES
(1, 'admin'),
(2, 'sub_admin'),
(3, 'gestion_commerciale'),
(4, 'logistique'),
(5, 'comptable'),
(6, 'vendeur'),
(7, 'utilisateur'),
(8, 'formateur'),
(9, 'apprenant');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `message_id` int NOT NULL,
  `message_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `datetime` datetime NOT NULL,
  `sender_user_id` int NOT NULL,
  `receiver_user_id` int DEFAULT NULL,
  `message_content` text NOT NULL,
  `message_read` tinyint(1) DEFAULT NULL,
  `message_answered` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`message_id`, `message_type`, `datetime`, `sender_user_id`, `receiver_user_id`, `message_content`, `message_read`, `message_answered`) VALUES
(1, 'conversation', '2024-06-24 09:52:59', 3, 4, 'Bonjour Roberto, franchement je voulais te dire que t\'es trop beau et que c\'est pas la peine que tu m\'offres un sandwich parce que j\'ai pas été sage !', 0, 0),
(2, 'conversation', '2024-06-24 09:54:12', 4, 3, 'Bonjour Morgane, merci c\'est gentil ! Eh bah pas de problème pour la peine je vais en manger deux ! Allez salut fréro !', 0, 0),
(3, 'conversation', '2024-06-24 09:52:59', 2, 4, 'guilain to roberto', 0, 0),
(4, 'conversation', '2024-06-24 09:53:59', 4, 2, 'roberto to guilain', 0, 0),
(5, 'conversation', '2024-06-24 19:52:59', 3, 4, 'On remet un texte t\'as vu parce que si y\'a pas assez de longueur on est pas prêt de voir le scroll et tout quoi bordel de merde', 0, 0),
(6, 'conversation', '2024-06-24 19:54:12', 4, 3, 'Eh bah ouais du coup faut blablater tout ça bref on va voir c\'est super drole quoi voila et sinon quelle est la différence entre un pigeon ?!\r\nEt bah oui !', 0, 0),
(7, 'conversation', '2024-06-24 19:58:59', 3, 4, 'Aujourd\'hui j\'ai mangé des champignons avec du riz, avec de la crème et comme je suis une grande folle j\'ai mis des crottes de nez dedans pour que ça croustille un peu, c\'était vraiment très bon comme des chocapics et c\'est tellement drôle que quand j\'y pense je fais des bruits de petite cochonne bien dodue', 0, 0),
(8, NULL, '2024-06-27 13:47:53', 4, 3, 'gregre', NULL, NULL),
(9, NULL, '2024-06-27 13:50:21', 4, 3, 'rere', NULL, NULL),
(10, NULL, '2024-06-27 13:50:35', 4, 3, 'rere', NULL, NULL),
(11, NULL, '2024-06-27 13:50:38', 4, 3, 'gregre', NULL, NULL),
(12, NULL, '2024-06-27 13:53:00', 4, 3, 'bite', NULL, NULL),
(13, NULL, '2024-06-27 13:53:26', 4, 3, 'vfdfvd', NULL, NULL),
(14, NULL, '2024-06-27 14:35:48', 3, 4, 'Test', NULL, NULL),
(15, NULL, '2024-06-28 06:55:24', 4, 3, 'test', NULL, NULL),
(16, NULL, '2024-06-28 06:55:31', 3, 4, 'bite', NULL, NULL),
(17, NULL, '2024-06-28 06:55:49', 4, 3, 'bon bah on dirait que ça marche pas', NULL, NULL),
(18, NULL, '2024-06-28 06:56:52', 3, 4, 'hehe', NULL, NULL),
(19, NULL, '2024-06-28 06:58:05', 4, 3, 'hehe', NULL, NULL),
(20, NULL, '2024-06-28 06:58:13', 3, 4, 'gregregr', NULL, NULL),
(21, NULL, '2024-06-28 06:58:22', 4, 3, 'coucou', NULL, NULL),
(22, NULL, '2024-06-28 06:58:31', 3, 4, 'ca va ?', NULL, NULL),
(23, NULL, '2024-06-28 07:05:04', 3, 4, 'Bonjour', NULL, NULL),
(24, NULL, '2024-06-28 07:05:12', 4, 3, 'Je m\'appele maurice', NULL, NULL),
(25, NULL, '2024-06-28 07:07:25', 3, 4, 'Coucou', NULL, NULL),
(26, NULL, '2024-06-28 07:09:06', 4, 3, 'ette', NULL, NULL),
(27, NULL, '2024-06-28 07:09:10', 3, 4, 'gfdgfd', NULL, NULL),
(28, NULL, '2024-06-28 07:10:16', 3, 4, 'test', NULL, NULL),
(29, NULL, '2024-06-28 07:12:49', 4, 3, 'et', NULL, NULL),
(30, NULL, '2024-06-28 07:12:58', 4, 3, 'et', NULL, NULL),
(31, NULL, '2024-06-28 07:16:19', 3, 4, 'test', NULL, NULL),
(32, NULL, '2024-06-28 07:16:31', 4, 3, 'test', NULL, NULL),
(33, NULL, '2024-06-28 07:31:31', 4, 3, 'caca', NULL, NULL),
(34, NULL, '2024-06-28 07:31:42', 3, 4, 'caca', NULL, NULL),
(35, NULL, '2024-06-28 07:33:07', 4, 3, 'ggee', NULL, NULL),
(36, NULL, '2024-06-28 07:33:46', 3, 4, 'caa', NULL, NULL),
(37, NULL, '2024-06-28 07:36:14', 4, 3, 'fefe', NULL, NULL),
(38, NULL, '2024-06-28 07:36:20', 3, 4, 'ht', NULL, NULL),
(39, NULL, '2024-06-28 07:37:10', 3, 4, 'gfdgfd', NULL, NULL),
(40, NULL, '2024-06-28 07:39:08', 4, 3, 'gfd', NULL, NULL),
(41, NULL, '2024-06-28 08:11:51', 3, 4, 'coucou', NULL, NULL),
(42, NULL, '2024-06-28 08:16:11', 3, 4, 'bonjour', NULL, NULL),
(43, NULL, '2024-06-28 08:17:06', 4, 3, 'jhg', NULL, NULL),
(44, NULL, '2024-06-28 08:18:50', 3, 4, 'gfd', NULL, NULL),
(45, NULL, '2024-06-28 08:22:40', 4, 3, 'gre', NULL, NULL),
(46, NULL, '2024-06-28 08:22:43', 3, 4, 'gre', NULL, NULL),
(47, NULL, '2024-06-28 08:26:23', 3, 4, 'gregr', NULL, NULL),
(48, NULL, '2024-06-28 08:30:04', 4, 3, 'hgfhgf', NULL, NULL),
(49, NULL, '2024-06-28 08:30:36', 4, 3, 'hgf', NULL, NULL),
(50, NULL, '2024-06-28 08:30:51', 4, 3, 'gfgfd', NULL, NULL),
(51, NULL, '2024-06-28 08:30:55', 4, 3, 'gf', NULL, NULL),
(52, NULL, '2024-06-28 08:32:16', 4, 3, 'gfd', NULL, NULL),
(53, NULL, '2024-06-28 08:32:23', 4, 3, 'fdfd', NULL, NULL),
(54, NULL, '2024-06-28 08:34:55', 4, 3, 'fefe', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `ref` varchar(255) NOT NULL,
  `brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `size` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `pattern` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `material` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `stock` int NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `discount` decimal(3,2) NOT NULL DEFAULT '1.00',
  `category` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image_1` varchar(255) NOT NULL,
  `image_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `image_3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `image_4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `avatar`, `group`) VALUES
(1, 'super', 'admin', 'super@admin.com', '$argon2id$v=19$m=65536,t=4,p=1$OW5XVHlqaktCS0hudnlHTA$2LZ2t5ehCOV4QeEAcD0ARL7fgk9WhGaqSsZ5MEr1OHQ', NULL, NULL),
(2, 'guilain', 'admin', 'guilain@admin.com', '$argon2id$v=19$m=65536,t=4,p=1$cUlHbkVtUXZITHEzdlBNdA$uX12BS4BjYDq8/uQW1GxKhiXXERCqNYtAqI1w/4hl/4', NULL, NULL),
(3, 'morgane', 'admin', 'morgane@admin.com', '$argon2id$v=19$m=65536,t=4,p=1$NkdGbFIwZzY1SDBacEh0Sw$ubz/vUmV9jXx5dR80zAOD7gQTtyoht5ZhKzQP/eb7FQ', NULL, NULL),
(4, 'roberto', 'admin', 'roberto@admin.com', '$argon2id$v=19$m=65536,t=4,p=1$V2xDZFduQXcxSEZwMnlNYQ$kpZavx+IKYGw+wtGOsj2rkMeUI5N1Bu/+4NKsBqrsFY', NULL, NULL),
(5, 'osias', 'admin', 'osias@admin.com', '$argon2id$v=19$m=65536,t=4,p=1$cERBLzl5R3FsWHE5Nm5Ubw$6grq+rt5BkmF5GKSwkGpmpNDFWjDJxiON3ZbPZF0DQc', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users_groups`
--

CREATE TABLE `users_groups` (
  `user_id` int NOT NULL,
  `user_group` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commentaries`
--
ALTER TABLE `commentaries`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`groupe_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commentaries`
--
ALTER TABLE `commentaries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `groups`
--
ALTER TABLE `groups`
  MODIFY `groupe_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
