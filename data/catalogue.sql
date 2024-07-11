-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : mer. 10 juil. 2024 à 07:05
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
  `user_id_1` int NOT NULL,
  `user_id_2` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `conversations`
--

INSERT INTO `conversations` (`id`, `user_id_1`, `user_id_2`) VALUES
(1, 4, 2),
(2, 6, 7),
(3, 6, 4);

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
(2, 'sub'),
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
  `message_read` tinyint(1) NOT NULL DEFAULT '0',
  `message_answered` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`message_id`, `message_type`, `datetime`, `sender_user_id`, `receiver_user_id`, `message_content`, `message_read`, `message_answered`) VALUES
(134, NULL, '2024-07-01 12:31:56', 4, 3, 'Bite', 1, NULL),
(135, 'image', '2024-07-01 12:46:28', 3, 4, '../messages-files-storage/d4cdc1ebae90b3ace57f.png', 1, NULL),
(136, NULL, '2024-07-01 12:57:18', 4, 3, 'test', 1, NULL),
(137, NULL, '2024-07-01 13:05:19', 3, 4, 'Test', 1, NULL),
(138, NULL, '2024-07-02 06:43:00', 4, 2, 'Coucou Guilain !', 1, NULL),
(141, NULL, '2024-07-04 07:41:01', 6, 7, 'blabla teste', 1, NULL),
(143, 'image', '2024-07-04 07:42:09', 6, 4, '../messages-files-storage/df2bcfb4ab294c4a3eef.jpg', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `cart_id` int DEFAULT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('en attente','en cours de traitement','expédiée','livrée','annulée') DEFAULT 'en attente',
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `cart_id`, `order_date`, `status`, `total_amount`, `shipping_address`, `payment_method`) VALUES
(15, 6, NULL, '2024-07-09 10:27:39', 'en attente', 105.80, 'chatfouin', 'Carte_bancaire'),
(16, 6, NULL, '2024-07-09 10:55:41', 'en attente', 2.00, 'huiuiuiuiuiu', 'PayPal'),
(17, 8, NULL, '2024-07-09 11:30:39', 'en attente', 243.00, 'rue reloud', 'Virement_bancaire'),
(18, 6, NULL, '2024-07-09 11:35:08', 'en attente', 12.90, 'shsrgoirsoigj', 'PayPal'),
(19, 6, NULL, '2024-07-09 12:13:44', 'en attente', 1.00, '01 La chaume contant magny cours 58470 rue du prés vert batiement 01 de la boustifaille', 'Virement_bancaire'),
(20, 6, NULL, '2024-07-09 13:06:51', 'en attente', 38.90, 'htiophtjhrt', 'PayPal'),
(21, 6, NULL, '2024-07-09 13:21:53', 'en attente', 156.00, 'qmojflqjfijq', 'Virement_bancaire');

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `cart_id` int DEFAULT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`id`, `cart_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(18, NULL, 15, 18, 2, 19.90),
(19, NULL, 15, 36, 1, 65.00),
(20, NULL, 15, 32, 1, 1.00),
(21, NULL, 16, 32, 1, 1.00),
(22, NULL, 16, 29, 1, 1.00),
(24, NULL, 17, 36, 1, 65.00),
(25, NULL, 17, 29, 1, 1.00),
(26, NULL, 17, 32, 1, 1.00),
(27, NULL, 17, 37, 1, 155.00),
(28, NULL, 17, 35, 1, 21.00),
(31, NULL, 18, 27, 1, 12.90),
(32, NULL, 19, 31, 1, 1.00),
(33, NULL, 20, 28, 1, 25.90),
(34, NULL, 20, 33, 1, 12.00),
(35, NULL, 20, 34, 1, 1.00),
(36, NULL, 21, 37, 1, 155.00),
(37, NULL, 21, 34, 1, 1.00);

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

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `ref`, `brand`, `size`, `color`, `pattern`, `material`, `gender`, `stock`, `price`, `discount`, `category`, `content`, `image_1`, `image_2`, `image_3`, `image_4`) VALUES
(18, '78945AS', 'Bibop', 'S', 'bleu', 'rayure', 'coton', 'femme', 12, 19.90, 0.00, 'T-shirt', 'izjf jfoizjafi oajezoif jzoaifoizejfio zejfizjf jfoizjafi oajezoif jzoaifoizejfio zejfizjf jfoizjafi oajezoif jzoaifoizejfio zejfizjf jfoizjafi oajezoif jzoaifoizejfio zejfizjf jfoizjafi oajezoif jzoaifoizejfio zejfizjf jfoizjafi oajezoif jzoaifoizejfio zejfizjf jfoizjafi oajezoif jzoaifoizejfio zejfizjf jfoizjafi oajezoif jzoaifoizejfio zejfizjf jfoizjafi oajezoif jzoaifoizejfio zejf', '/img/upload_model/f6da5ef76542c2420e18f64129983f90.png', '/img/upload_model/f26e44fe553edb562a64c6cf6a1752d4.png', '/img/upload_model/202d979d351f2566c511667de256b483.jpeg', '/img/upload_model/befefd0666807ac9026df5daa0a52ef8.jpg'),
(25, '8946513', 'Chappy', 'XS', 'bleu', 'rayure', 'coton', 'homme', 1, 1.00, 1.00, 'Jupe', 'efje oifjezjf oezifoi zejfio jzofj eozjfoezjoefje oifjezjf oezifoi zejfio jzofj eozjfoezjoefje oifjezjf oezifoi zejfio jzofj eozjfoezjoefje oifjezjf oezifoi zejfio jzofj eozjfoezjoefje oifjezjf oezifoi zejfio jzofj eozjfoezjoefje oifjezjf oezifoi zejfio jzofj eozjfoezjoefje oifjezjf oezifoi zejfio jzofj eozjfoezjoefje oifjezjf oezifoi zejfio jzofj eozjfoezjoefje oifjezjf oezifoi zejfio jzofj eozjfoezjoefje oifjezjf oezifoi zejfio jzofj eozjfoezjo', '/img/upload_model/5ca6adb9e8d452be8ec40fac81371d44.png', '/img/upload_model/fb1e03242327d9f261eab799a08e0a47.png', '/img/upload_model/ccea7c329d9521cb51f68b3d0a353c3d.jpeg', '/img/upload_model/6013eebb2447cf2caca890d9fd107eb8.png'),
(26, '94851as', 'Peluche', 'S', 'rouge', 'losange', 'polyestere', 'femme', 1, 23.00, 0.00, 'Pull', 'efn ezofoienf oznefjze foiezfoiezjoifj ezojfoiez efn ezofoienf oznefjze foiezfoiezjoifj ezojfoiez efn ezofoienf oznefjze foiezfoiezjoifj ezojfoiez efn ezofoienf oznefjze foiezfoiezjoifj ezojfoiez efn ezofoienf oznefjze foiezfoiezjoifj ezojfoiez efn ezofoienf oznefjze foiezfoiezjoifj ezojfoiez efn ezofoienf oznefjze foiezfoiezjoifj ezojfoiez efn ezofoienf oznefjze foiezfoiezjoifj ezojfoiez efn ezofoienf oznefjze foiezfoiezjoifj ezojfoiez ', '/img/upload_model/c223bec7d43124ce3b24c62620b89b21.webp', '', '', ''),
(27, '894894rre', 'Tortue', 'S', 'rouge', 'carre', 'cuir', 'femme', 1, 12.90, 0.00, 'Veste', 'ouebgoezgouebgou zbog bzb ouebgoezgouebgou zbog bzb ouebgoezgouebgou zbog bzb ouebgoezgouebgou zbog bzb ouebgoezgouebgou zbog bzb ouebgoezgouebgou zbog bzb ouebgoezgouebgou zbog bzb ouebgoezgouebgou zbog bzb ouebgoezgouebgou zbog bzb ouebgoezgouebgou zbog bzb ouebgoezgouebgou zbog bzb ', '/img/upload_model/c66196added01fb578360cee82aabccd.png', '/img/upload_model/310c2ffde4c932e877020e27a6c02784.jpg', '/img/upload_model/8c5254be61a55ed53152193ec3f0892e.png', '/img/upload_model/fcd9b5391ccb5eb7403d910b9060fa2f.jpg'),
(28, '89741ER', 'Bullot', 'M', 'bleu', 'losange', 'polyestere', 'femme', 14, 25.90, 1.00, 'Robe', 'ueznf oneoz fnoznfo znoifnzeofn eoznueznf oneoz fnoznfo znoifnzeofn eoznueznf oneoz fnoznfo znoifnzeofn eoznueznf oneoz fnoznfo znoifnzeofn eoznueznf oneoz fnoznfo znoifnzeofn eoznueznf oneoz fnoznfo znoifnzeofn eoznueznf oneoz fnoznfo znoifnzeofn eozn', '/img/upload_model/a2aa06bd5c64d58ab4575f79c93ea21f.jpg', '/img/upload_model/5f09f9481b99633de0f278dbc28d14bc.jpg', '/img/upload_model/6005e7aa942e3d3678c073237b58bfc5.jpg', '/img/upload_model/a303f800f6b04c72012bcf29c9da085b.jpg'),
(29, '89416zd', 'Lapin', 'L', 'rouge', 'losange', 'coton', 'femme', 1, 1.00, 1.00, 'Veste', 'ejnoez nfono znofneio oingoire znejnoez nfono znofneio oingoire znejnoez nfono znofneio oingoire znejnoez nfono znofneio oingoire znejnoez nfono znofneio oingoire znejnoez nfono znofneio oingoire znejnoez nfono znofneio oingoire znejnoez nfono znofneio oingoire znejnoez nfono znofneio oingoire znejnoez nfono znofneio oingoire zn', '/img/upload_model/9fd4d1ca9ddfe65d7ff3ae3df7b38ea9.jpg', '/img/upload_model/4e929709cc75d0829174979d76895525.jpg', '/img/upload_model/67a8e6747fd5029512fb1c2d6cfa42bf.jpg', '/img/upload_model/7a34e2fe22a26fa64b9588b6833cf8a7.jpg'),
(30, '98189191', 'Bugs ', 'M', 'rouge', 'rayure', 'polyestere', 'homme', 13, 12.00, 1.00, 'Pull', 'IAOFHOIo feifj iozejfio ejzio fjziojf zejfozejiozfe jIAOFHOIo feifj iozejfio ejzio fjziojf zejfozejiozfe jIAOFHOIo feifj iozejfio ejzio fjziojf zejfozejiozfe jIAOFHOIo feifj iozejfio ejzio fjziojf zejfozejiozfe jIAOFHOIo feifj iozejfio ejzio fjziojf zejfozejiozfe jIAOFHOIo feifj iozejfio ejzio fjziojf zejfozejiozfe jIAOFHOIo feifj iozejfio ejzio fjziojf zejfozejiozfe j', '/img/upload_model/07e39223475ed7514aa8d0e9448bbc8a.jpg', '/img/upload_model/dde038a5e06822ec02f54c03cce11f78.jpeg', '/img/upload_model/d6bf14418816ebd1bfed277357bae083.png', '/img/upload_model/2d4b37e545a59d27e964c4b4dc66f0be.jpg'),
(31, '489165', 'Bunny', 'S', 'rouge', 'carre', 'polyestere', 'homme', 1, 1.00, 1.00, 'Pantalon', 'ioefoiejf oizejofejzo fjoize e zoioefoiejf oizejofejzo fjoize e zoioefoiejf oizejofejzo fjoize e zoioefoiejf oizejofejzo fjoize e zoioefoiejf oizejofejzo fjoize e zoioefoiejf oizejofejzo fjoize e zoioefoiejf oizejofejzo fjoize e zoioefoiejf oizejofejzo fjoize e zoioefoiejf oizejofejzo fjoize e zoioefoiejf oizejofejzo fjoize e zo', '/img/upload_model/3bf8ad16983be3ed373be091a797129a.jpg', '/img/upload_model/ff4aadd53377fa99a60fa9370abfcd60.png', '/img/upload_model/709862044c6b4b2805c8d7c3e8a64353.jpg', '/img/upload_model/3ee66141ec4ab81a34c5f16f28a99eb4.jpeg'),
(32, '89416HJ', 'Silent', 'XS', 'bleu', 'carre', 'cuir', 'homme', 1, 1.00, 0.00, 'T-shirt', 'enfoef oiejfijz iojorjg oirjgoirej jgreo ogj', '/img/upload_model/fbaac4d4002fba81fc7b8081135caa48.png', '', '', ''),
(33, '9874516', 'Fugu', 'M', 'bleu', 'rayure', 'coton', 'homme', 12, 12.00, 0.00, 'Veste', 'ziofiozfjio efoi ejzfjezojf oiezjfj zeojziofiozfjio efoi ejzfjezojf oiezjfj zeojziofiozfjio efoi ejzfjezojf oiezjfj zeojziofiozfjio efoi ejzfjezojf oiezjfj zeojziofiozfjio efoi ejzfjezojf oiezjfj zeojziofiozfjio efoi ejzfjezojf oiezjfj zeojziofiozfjio efoi ejzfjezojf oiezjfj zeojziofiozfjio efoi ejzfjezojf oiezjfj zeojziofiozfjio efoi ejzfjezojf oiezjfj zeojziofiozfjio efoi ejzfjezojf oiezjfj zeojziofiozfjio efoi ejzfjezojf oiezjfj zeojziofiozfjio efoi ejzfjezojf oiezjfj zeoj', '/img/upload_model/0669ffba6c7bbf515a06b934e3c5aa4e.png', '/img/upload_model/e11bee3b78b518b1f2a2b37c5c166176.jpg', '/img/upload_model/e2ea999ed4ce3341aa43644897c202e1.png', '/img/upload_model/7e7803f5b88867a2fcdc3faa1dbc0584.png'),
(34, '48984D', 'bUNNY', 'XS', 'rouge', 'rayure', 'coton', 'femme', 1, 1.00, 1.00, 'Veste', 'eflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen g', '/img/upload_model/fe6d24c8fdbf60a9319928ab3d7d6c20.jpg', '/img/upload_model/319997e6c3e7d6c1a64b9593edac6e4a.jpeg', '/img/upload_model/260fb351771b3778c1249ad35b3dc07b.png', '/img/upload_model/fb1c067d83bb6c0b4687326f5da8a494.png'),
(35, '65489fd', 'Lola', 'L', 'rouge', 'carre', 'polyestere', 'femme', 1, 21.00, 0.00, 'Veste', 'eflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen g', '/img/upload_model/b818843d10c8ee879b933d2f8f4fa825.png', '/img/upload_model/89ece434333dee4c8e5559b639449724.png', '/img/upload_model/b1bbf171b86ee029f523bc57eff511b6.jpg', '/img/upload_model/e11562a8893894c9ad047bb688a70dd0.jpg'),
(36, '44894854', 'Coyotyto', 'M', 'bleu', 'losange', 'polyestere', 'femme', 1, 65.00, 0.00, 'Veste', 'efbek ezkjf nkeflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen g', '/img/upload_model/bef91caaa5fb29a954429912903c1d12.jpg', '/img/upload_model/466f5ab619d82a22501d504a89f60a73.png', '', ''),
(37, '8441915', 'Little', 'L', 'bleu', 'losange', 'cuir', 'homme', 12, 155.00, 1.00, 'Veste', 'eflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen geflkeneglkzeezlgnez zrign rng rngergrn rlen g', '/img/upload_model/4979e97dafd12e0d1238a0cbbf8cf3de.png', '', '', '');

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
  `Linkedin` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `Linkedin`, `avatar`, `group`) VALUES
(1, 'super', 'admin', 'super@admin.com', '$argon2id$v=19$m=65536,t=4,p=1$OW5XVHlqaktCS0hudnlHTA$2LZ2t5ehCOV4QeEAcD0ARL7fgk9WhGaqSsZ5MEr1OHQ', NULL, NULL, 'sub-admin,utilisateur'),
(2, 'guilain', 'painsec', 'guilain@admin.com', '$argon2id$v=19$m=65536,t=4,p=1$cUlHbkVtUXZITHEzdlBNdA$uX12BS4BjYDq8/uQW1GxKhiXXERCqNYtAqI1w/4hl/4', '', '../img/upload_avatars/profile_66868c1b2a090.png', 'admin,formateur,apprenant,comptable'),
(3, 'morgane', 'lerein', 'morgane@admin.com', '$argon2id$v=19$m=65536,t=4,p=1$NkdGbFIwZzY1SDBacEh0Sw$ubz/vUmV9jXx5dR80zAOD7gQTtyoht5ZhKzQP/eb7FQ', NULL, NULL, 'admin,formateur,apprenant,gestion commercial'),
(4, 'roberto', 'zigoto', 'roberto@admin.com', '$argon2id$v=19$m=65536,t=4,p=1$V2xDZFduQXcxSEZwMnlNYQ$kpZavx+IKYGw+wtGOsj2rkMeUI5N1Bu/+4NKsBqrsFY', NULL, NULL, 'admin,apprenant,logistique'),
(5, 'osias', 'chorizo', 'osias@admin.com', '$argon2id$v=19$m=65536,t=4,p=1$cERBLzl5R3FsWHE5Nm5Ubw$6grq+rt5BkmF5GKSwkGpmpNDFWjDJxiON3ZbPZF0DQc', NULL, NULL, 'admin,apprenant,gestion commercial,logistique,comptable,vendeur'),
(6, 'Yrautcnas', 'Keryoh', 'yrautcnas@msn.com', '$argon2id$v=19$m=65536,t=4,p=1$OFE5V3dKeWM2dlVpWWhiWg$RFNrYEQjT4kzIUjJFOP2sOGMfKynq0j7IaWkV4V2Y6g', 'https://www.linkedin.com/in/guilain-de-meyer-3aa4a5317/', '../img/upload_avatars/profile_66864c572cacf.png', 'admin,sub,formateur,apprenant,gestion commercial,logistique,comptable,vendeur,utilisateur'),
(7, 'Blaireau', 'Super', 'SB@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$VW5Bd2ZUWmg0YkhWSmx4bg$9ygrpgB0u2FdJ4HXz4+hudNBi6AqMj2Bwm1mm6roSS4', '', '../img/upload_avatars/profile_66865ec2eceaa.jpg', 'sub,apprenant'),
(8, 'Zag', 'Zig', 'Zigzag@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$eDczQXVZMHhoTkRyNzFxUw$EdfEgSlJLcYDOuWwCfdLFtmyty+uerVf36Bm1WVno98', NULL, NULL, 'user');

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
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `fk_cart_id` (`cart_id`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pour la table `commentaries`
--
ALTER TABLE `commentaries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `groups`
--
ALTER TABLE `groups`
  MODIFY `groupe_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_cart_id` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`),
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
