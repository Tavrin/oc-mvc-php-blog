-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 14, 2021 at 06:12 PM
-- Server version: 8.0.23-0ubuntu0.20.04.1
-- PHP Version: 7.3.28-1+ubuntu20.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mvc_blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `media_id` int DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `status` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `created_at`, `updated_at`, `name`, `description`, `path`, `slug`, `uuid`, `media_id`, `meta_description`, `meta_title`, `status`) VALUES
(3, '2021-04-17 20:00:22', NULL, 'Backend dev', 'développement web backend', '/blog/backend-dev', 'backend-dev', '6ddb32ef-5900-40f8-af82-0f2daa6bed74', 12, 'aa', 'gg', 0),
(4, '2021-04-12 20:00:22', NULL, 'Frontend dev', 'Dev frontend', '/blog/frontend-dev', 'frontend-dev', 'a7d27240-9d73-4967-a31c-b2049894431e', 26, 'bb', 'aa', 1),
(5, '2021-04-19 10:57:27', NULL, 'Game Dev', 'Tout ce qui concerne le développement de jeux vidéo', '/blog/game-dev', 'game-dev', '668ee14e-6880-4f1e-9cbf-42afc5edf2bc', 12, 'Tout ce qui concerne le développement de jeux vidéo', 'Game Dev', 1),
(7, '2021-04-29 01:52:00', NULL, 'Digital Painting', '', '/blog/digital-painting', 'digital-painting', '16839eb0-8310-494a-910a-fa8e066cc847', 22, 'Digital Painting', 'Digital Painting', 1),
(8, '2021-04-29 01:52:53', NULL, 'Culinaire', '', '/blog/culinaire', 'culinaire', '3a4059d8-fa59-4069-a3d0-a5370d57a2cd', 10, 'Tout ce qui concerne mes recettes', 'Culinaire', 1);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `content` mediumtext NOT NULL,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL,
  `status` tinyint NOT NULL,
  `slug` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `hidden` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `created_at`, `updated_at`, `content`, `user_id`, `post_id`, `status`, `slug`, `path`, `hidden`) VALUES
(14, '2021-01-12 19:30:54', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vitae lobortis ex, vestibulum lobortis lectus. Nulla lacinia metus nec massa ultricies, sit amet iaculis felis accumsan. Nam magna elit, rutrum nec efficitur blandit, vestibulum vel lectus. Nullam in maximus risus, non eleifend mauris. Etiam aliquam sem massa, eget viverra ligula vulputate a. Suspendisse ut eros vitae nisl pulvinar euismod. Vestibulum facilisis mauris vitae sem efficitur, eu varius nunc vehicula. Duis sagittis laoreet porta. Vivamus eu sem venenatis, fringilla risus sed, lobortis dui. Nulla facilisi. Maecenas mattis pellentesque risus nec pulvinar.', 18, 35, 1, 'comment-xkh87hj05f', '/blog/game-dev/comment-poster-un-article-sur-ce-blog/comment-xtowdz1k5n#comment-xkh87hj05f', 0),
(15, '2021-04-25 02:54:42', NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eget dapibus ex, molestie dignissim nisl. Suspendisse cursus leo lacinia velit rhoncus, quis condimentum sapien tincidunt. Nulla eget est et nibh condimentum scelerisque ac ac nisl. Etiam pellentesque id erat non euismod. Nam sodales ultrices neque, vitae sollicitudin orci dictum nec. Sed vehicula nisl nec tempus malesuada. Nulla ut augue tellus. Nulla facilisi.', 1, 35, 1, 'comment-hyoodl1p8d', '/blog/game-dev/comment-poster-un-article-sur-ce-blog#comment-hyoodl1p8d', 0),
(16, '2021-04-28 02:27:45', NULL, 'Test commentaire', 18, 35, 1, 'comment-xtowdz1k5n', '/blog/game-dev/comment-poster-un-article-sur-ce-blog#comment-xtowdz1k5n', 0),
(17, '2021-04-28 16:16:47', NULL, 'nouveau commentaire', 18, 35, 1, 'comment-nzfkihg2ta', '/blog/game-dev/comment-poster-un-article-sur-ce-blog#comment-nzfkihg2ta', 0),
(18, '2021-05-03 18:59:33', NULL, 'Test commentaire bis', 18, 35, 1, 'comment-ef9jr0clgx', '/blog/frontend-dev/comment-poster-un-article-sur-ce-blog#comment-ef9jr0clgx', 0);

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  `uuid` varchar(45) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `media_type_id` int NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `created_at`, `updated_at`, `name`, `alt`, `path`, `uuid`, `status`, `media_type_id`, `slug`) VALUES
(10, '2021-04-18 21:17:31', '2021-04-18 21:17:31', 'test nouvelle image', 'image', '/uploads/media/image/test-nouvelle-image.jpg', '9cb70b57-45de-4986-b7ea-7c28819d9d9f', 1, 2, 'test-nouvelle-image'),
(12, '2021-04-25 05:13:56', '2021-04-25 05:13:56', 'test-form-media', 'aaa', '/uploads/media/image/test-form-media.jpg', 'f7a63992-be81-47d1-8141-3a7678b4c9f6', 1, 2, 'test-form-media'),
(22, '2021-04-26 13:20:48', '2021-04-26 13:20:48', 'image pour test post', 'illustration par Etienne', '/uploads/media/image/image-pour-test-post.jpg', '2da7a33f-0a4a-46d4-8e8e-e799af6014ad', 1, 2, 'image-pour-test-post'),
(24, '2021-04-26 18:47:47', '2021-04-26 18:47:47', 'test ajax', 'aaa', '/uploads/media/image/test-ajax.png', 'bdf49394-4812-42ef-9a16-150b883e8260', 1, 2, 'test-ajax'),
(25, '2021-04-26 18:57:17', '2021-04-26 18:57:17', 'banderole', 'banderole', '/uploads/media/image/banderole.jpeg', '38e25f68-5d05-4118-90c5-f62db2c6057f', 1, 2, 'banderole'),
(26, '2021-04-29 01:56:17', '2021-04-29 01:56:17', 'frontend image', 'frontend image', '/uploads/media/image/frontend-image.jpg', '162f91a3-6d76-49fc-8dc8-e68b330d9893', 1, 2, 'frontend-image'),
(27, '2021-05-14 13:21:16', '2021-05-14 13:21:16', 'dandan-2', 'dandan-2', '/uploads/media/image/dandan-2.jpg', 'b16f1f15-8f12-4e5d-9b85-2efb9d677c68', 1, 2, 'dandan-2');

-- --------------------------------------------------------

--
-- Table structure for table `media_type`
--

CREATE TABLE `media_type` (
  `id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `media_type`
--

INSERT INTO `media_type` (`id`, `created_at`, `updated_at`, `name`, `uuid`, `status`, `slug`) VALUES
(2, '2021-04-17 14:32:37', NULL, 'Image', 'cdb279fb-0876-48db-8b18-b8bcf184db90', 1, 'image');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `full_name`, `email`, `content`, `created_at`, `slug`, `uuid`) VALUES
(2, '&lt;script&gt;alert(\'test\')&lt;/script&gt;', 'etienne.doux@gmail.com', '&lt;script&gt;alert(\'test\')&lt;/script&gt;', '2021-05-07 03:44:41', 'message-b190da6a-a027-4cd1-a76a-483002385825', 'b190da6a-a027-4cd1-a76a-483002385825'),
(3, 'etienne doux', 'etienne.doux@gmail.com', 'test &lt;script&gt;console.log(\'test\')&lt;/script', '2021-05-10 19:19:19', 'message-bafde9ae-b040-4b91-9127-a1c447b042b4', 'bafde9ae-b040-4b91-9127-a1c447b042b4');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `header` mediumtext NOT NULL,
  `content` mediumtext NOT NULL,
  `readmore` json DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `status` tinyint NOT NULL,
  `category_id` int NOT NULL,
  `path` varchar(255) NOT NULL,
  `media_id` int DEFAULT NULL,
  `featured` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `created_at`, `updated_at`, `title`, `header`, `content`, `readmore`, `meta_title`, `meta_description`, `slug`, `user_id`, `status`, `category_id`, `path`, `media_id`, `featured`) VALUES
(27, '2021-04-13 02:30:48', '2021-04-13 10:44:22', 'test article mis à jour', '{\"time\":1618303462265,\"blocks\":[{\"type\":\"paragraph\",\"data\":{\"text\":\"aaa\",\"alignment\":\"left\"}},{\"type\":\"paragraph\",\"data\":{\"text\":\"bbb\",\"alignment\":\"left\"}}],\"version\":\"2.20.1\",\"id\":\"editorjsChapo\"}', '{\"time\":1618303462274,\"blocks\":[{\"type\":\"paragraph\",\"data\":{\"text\":\"bbb\",\"alignment\":\"left\"}}],\"version\":\"2.20.1\",\"id\":\"editorjsContent\"}', '[]', 'méta titre', 'méta description', 'test-article-mis-a-jour', 18, 1, 4, '/blog/dev-backend/test-article', NULL, 0),
(28, '2021-04-13 04:44:53', '2021-04-13 11:32:25', 'aaa', '{\"time\":1618696014502,\"blocks\":[{\"type\":\"paragraph\",\"data\":{\"text\":\"vvv\",\"alignment\":\"left\"}},{\"type\":\"header\",\"data\":{\"text\":\"qpcqskcnqmckqn\",\"level\":2}},{\"type\":\"paragraph\",\"data\":{\"text\":\"data:image/jpeg;base64,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\",\"alignment\":\"left\"}}],\"version\":\"2.20.2\",\"id\":\"editorjsChapo\"}', '{\"time\":1618696014513,\"blocks\":[{\"type\":\"paragraph\",\"data\":{\"text\":\"gggggggggg\",\"alignment\":\"left\"}}],\"version\":\"2.20.2\",\"id\":\"editorjsContent\"}', '[]', 'bbb', 'ccc', 'aaa', 18, 1, 3, '/blog/dev-backend/aaa', NULL, 0),
(30, '2021-04-13 11:53:03', '2021-04-16 04:06:24', 'nouveau post', '{\"time\":1618695972394,\"blocks\":[{\"type\":\"paragraph\",\"data\":{\"text\":\"bbb\",\"alignment\":\"left\"}}],\"version\":\"2.20.2\",\"id\":\"editorjsChapo\"}', '{\"time\":1618695972404,\"blocks\":[{\"type\":\"paragraph\",\"data\":{\"text\":\"ccc\",\"alignment\":\"left\"}}],\"version\":\"2.20.2\",\"id\":\"editorjsContent\"}', '[]', 'taaa', 'mlvjsmvlsj', 'nouveau-post', 18, 1, 3, '/blog/dev-backend/nouveau-post', NULL, 0),
(31, '2021-04-16 05:02:18', '2021-04-17 20:19:47', 'bbb', '{\"time\":1618683902484,\"blocks\":[{\"type\":\"paragraph\",\"data\":{\"text\":\"aaa\",\"alignment\":\"left\"}}],\"version\":\"2.20.2\",\"id\":\"editorjsChapo\"}', '{\"time\":1618683902494,\"blocks\":[{\"type\":\"paragraph\",\"data\":{\"text\":\"bbb\",\"alignment\":\"left\"}}],\"version\":\"2.20.2\",\"id\":\"editorjsContent\"}', '[]', 'aaa', 'ccc', 'bbb', 18, 1, 3, '/blog/frontend-dev/bbb', NULL, 0),
(32, '2021-04-19 11:40:51', '2021-05-14 13:13:12', 'Mon premier prototype sous Unity', '{\"time\":1620994060232,\"blocks\":[{\"id\":\"4b_uqvnWOU\",\"type\":\"paragraph\",\"data\":{\"text\":\"Mon premier prototype sous Unity, un jeu en 2D topdown\",\"alignment\":\"left\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsChapo\"}', '{\"time\":1620994060236,\"blocks\":[{\"id\":\"Ji5OuZvIN_\",\"type\":\"header\",\"data\":{\"text\":\"Prémices du projet\",\"level\":2},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}}],\"version\":\"2.21.0\",\"id\":\"editorjsContent\"}', '[]', 'premier prototype', 'Mon premier prototype sous Unity, un jeu en 2D topdown', 'mon-premier-prototype-sous-unity', 18, 1, 5, '/blog/game-dev/mon-premier-prototype-sous-unity', 22, 0),
(34, '2021-04-24 01:33:39', '2021-05-14 14:08:39', 'Comment prévenir une attaque xss', '{\"time\":1620994359460,\"blocks\":[{\"id\":\"0vzUGyyxgq\",\"type\":\"paragraph\",\"data\":{\"text\":\"Vous voulez savoir comment les formulaires de ce site protègent d\'une attaque xss ? C\'est par ici\",\"alignment\":\"left\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsChapo\"}', '{\"time\":1620994359464,\"blocks\":[{\"id\":\"l3jF3urKVx\",\"type\":\"header\",\"data\":{\"text\":\"Champs cachés\",\"level\":2},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"gsA8HK3Qbt\",\"type\":\"paragraph\",\"data\":{\"text\":\"Ce site utilise un champ caché avec un nouveau token CSRF généré à chaque requête, ainsi une tentative de cross site scripting serait infructueuse car le CSRF serait inconnu de l\'attaquant.\",\"alignment\":\"left\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsContent\"}', '[]', 'prévention attaque xss', 'Comment prévenir une attaque xss, description de la manière de faire de ce blog', 'comment-prevenir-une-attaque-xss', 18, 1, 4, '/blog/frontend-dev/comment-prevenir-une-attaque-xss', 24, 0),
(35, '2021-04-25 15:59:06', '2021-04-29 19:08:17', 'Comment poster un article sur ce blog', '{\"time\":1620062393119,\"blocks\":[{\"id\":\"oNbFdSKV1L\",\"type\":\"header\",\"data\":{\"text\":\"Titre du chapo\",\"level\":1},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"tfHTQjflm6\",\"type\":\"paragraph\",\"data\":{\"text\":\"Text\",\"alignment\":\"left\"}},{\"id\":\"c0GEDoT__8\",\"type\":\"paragraph\",\"data\":{\"text\":\"Lorem ipsum dolor sit amet, <a href=\\\"https://google.com\\\">consectetur</a> adipiscing elit. Fusce nec magna quis odio consequat semper sed elementum lorem. Aenean massa lorem, lacinia a nunc quis, consequat luctus libero. Fusce gravida facilisis dignissim. Suspendisse dapibus leo eu nibh pellentesque, rutrum efficitur nunc molestie. Ut scelerisque, metus non facilisis sagittis, nisl quam ultricies elit, in efficitur mauris ante nec urna. Aliquam sodales metus finibus, finibus nibh vel, interdum sapien. Curabitur vulputate eu tortor id finibus. Fusce quis est gravida, laoreet urna id, sollicitudin enim. Mauris hendrerit vestibulum erat, rutrum commodo nunc.\",\"alignment\":\"left\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsChapo\"}', '{\"time\":1620062393126,\"blocks\":[{\"id\":\"ksq3DG3kG6\",\"type\":\"header\",\"data\":{\"text\":\"Contenu de l\'article\",\"level\":2},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"3X3XEVKdBs\",\"type\":\"paragraph\",\"data\":{\"text\":\"Suspendisse rutrum dui ac suscipit convallis. Curabitur ut dui sed nulla maximus vehicula. Etiam mattis posuere est ut ultricies. Suspendisse convallis semper ex non semper. Morbi fringilla facilisis varius. Mauris pellentesque volutpat convallis. Nunc vel dolor ligula. Nam libero metus, cursus nec felis et, laoreet vulputate elit. Proin ultrices ultricies elit eget malesuada. Praesent pulvinar ipsum purus, nec posuere sem vulputate sit amet. Maecenas in dapibus urna, ut eleifend purus. Pellentesque mollis magna tellus, vitae suscipit velit tempor quis. Maecenas non nisi consectetur urna dictum suscipit. In a nisl at libero rhoncus placerat quis quis mi. Vestibulum a porttitor ipsum.\",\"alignment\":\"left\"}},{\"id\":\"W49dJmLE6p\",\"type\":\"paragraph\",\"data\":{\"text\":\"Aliquam dui risus, volutpat sed convallis ut, eleifend ac nisi. Proin consequat ante ut nunc rutrum, sed lacinia ante imperdiet. Nulla non mattis eros, vitae mattis lacus. Donec sed purus suscipit, auctor nisl at, euismod felis. Curabitur aliquet nisl non vehicula ullamcorper. Ut gravida metus sit amet metus aliquam, ut porta libero semper. Sed placerat iaculis ipsum, eget malesuada ipsum fermentum nec.\",\"alignment\":\"left\"}},{\"id\":\"BEmWa0nntJ\",\"type\":\"code\",\"data\":{\"code\":\"$postRepository = new PostRepository($this->getManager());\\n        $commentRepository = new CommentRepository($this->getManager());\\n        $categoryRepository = new CategoryRepository($this->getManager());\\n        $comment = new Comment();\\n        $commentForm = new CommentForm($request,$comment, $this->session, [\'name\' => \'commentForm\', \'wrapperClass\' => \'mb-1\']);\\n        $blogManager = new BlogManager($this->getManager(), $postRepository);\\n        $postData = $this->getManager()->getEntityData(\'post\');\\n        $post = $postRepository->findOneBy(\'slug\', $slug);\\n        $content[\'post\'] = $blogManager->hydratePost($post, $postData);\\n        $content[\'comments\'] = $commentRepository->findBy(\'post_id\', $post->getId(), \'created_at\', \'DESC\');\\n        $content[\'categories\'] = $categoryRepository->findAll();\\n\\n        $commentForm->handle($request);\\n\\n        if ($commentForm->isSubmitted) {\\n            if ($commentForm->isValid) {\\n                $comment->setContent($commentForm->getData(\'Commentaire\'));\\n                if (true === $blogManager->saveComment($comment, $post, $this->getUser())) {\\n                    $this->redirect($post->getPath(), [\'type\' => \'success\', \'message\' => \\\"Commentaire en attente de validation\\\"]);\\n                }\\n            }\\n\\n            $this->redirect($post->getPath(), [\'type\' => \'danger\', \'message\' => \\\"Une erreur s\'est produite, le commentaire n\'a pas pu être enregistré\\\"]);\\n        }\\n\\n            return $this->render(\'blog/show.html.twig\', [\\n            \'content\' => $content,\\n            \'form\' => $commentForm->renderForm()\\n        ]);\\n    }\",\"languageCode\":\"php\"}},{\"id\":\"3Zo5wDq13a\",\"type\":\"paragraph\",\"data\":{\"text\":\"Nullam sodales, purus quis condimentum commodo, nunc dui dignissim lacus, vel aliquet metus elit ac sem. Etiam elit velit, consectetur eget vestibulum sed, efficitur nec tellus. Morbi euismod nunc in dolor rutrum blandit. In auctor dignissim magna id aliquet. Praesent blandit urna arcu, ut maximus elit fringilla non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Etiam laoreet libero nec aliquam eleifend. Quisque ornare convallis metus, in condimentum nibh gravida non. Duis vitae arcu quis metus hendrerit convallis ut id nunc. Interdum et malesuada fames ac ante ipsum primis in faucibus.\",\"alignment\":\"left\"}},{\"id\":\"2SO2hlQZ55\",\"type\":\"header\",\"data\":{\"text\":\"Titre 2 de l\'article\",\"level\":2},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"nZjzXYlxFK\",\"type\":\"paragraph\",\"data\":{\"text\":\"Suspendisse tellus sem, iaculis eu risus in, molestie sodales orci. Aenean accumsan odio risus, in accumsan nisl vestibulum tincidunt. Pellentesque interdum mollis eleifend. Aliquam id fermentum nibh. Donec felis velit, consequat non venenatis eget, aliquet eget justo. Cras nec odio interdum, pretium sem vel, viverra nulla. Integer sollicitudin gravida ante, id elementum ante pretium ac. Aenean accumsan venenatis tincidunt. Integer sed massa id nisi vestibulum blandit quis elementum massa. Nunc eget cursus velit, non lobortis ante. Praesent finibus tristique lacus, et posuere nulla viverra a.\",\"alignment\":\"left\"}},{\"id\":\"LrhHKUkx7S\",\"type\":\"paragraph\",\"data\":{\"text\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae lacus non est dapibus efficitur. Donec blandit dictum justo. Praesent tincidunt erat non sapien dignissim vulputate. Vivamus egestas maximus elit eget viverra. In vel facilisis turpis, nec laoreet dui. Proin eu tortor massa. Nulla facilisis neque non dui auctor lobortis. Vestibulum tellus dolor, volutpat a sapien non, scelerisque sagittis eros. Duis ac nulla et quam viverra rhoncus at non dui. Aliquam aliquam metus rutrum sem vehicula ultricies. Praesent aliquam, magna a ornare scelerisque, velit orci malesuada massa, iaculis egestas leo enim malesuada velit. Duis sit amet mi lacinia, dignissim quam ut, pellentesque elit.\",\"alignment\":\"left\"}},{\"id\":\"-o9jepNTTL\",\"type\":\"mediaPicker\",\"data\":{\"url\":\"http://localhost:8001/uploads/media/image/image-pour-test-post.jpg\",\"caption\":\"Emerging par Etienne Doux\",\"mediaType\":\"image\"}},{\"id\":\"TjIHAGF890\",\"type\":\"paragraph\",\"data\":{\"text\":\"Integer sapien ligula, molestie non felis quis, venenatis sagittis nisi. Quisque in tortor ut velit facilisis fringilla non et ligula. Proin justo lectus, egestas vitae aliquam at, feugiat vitae tellus. Vivamus semper urna ac elementum lacinia. Cras molestie dui ac elit placerat sollicitudin a a lacus. Nunc ullamcorper lacus vitae porttitor cursus. Fusce sit amet malesuada est. Fusce quis nulla et mi consectetur condimentum. Nulla volutpat neque nec dui tincidunt dignissim. Morbi mollis efficitur facilisis. Curabitur varius, turpis at aliquam congue, ligula est fringilla metus, non vestibulum libero augue ac ligula. Aliquam aliquam dolor non vehicula elementum. Ut nec neque rutrum, molestie nunc id, sollicitudin ex. Praesent eu justo accumsan, condimentum metus eget, viverra diam. Nulla facilisi.\",\"alignment\":\"left\"}},{\"id\":\"MvHQLEymGi\",\"type\":\"paragraph\",\"data\":{\"text\":\"Sed dapibus tortor eget semper venenatis. Fusce lobortis sem at diam iaculis laoreet. Nunc sollicitudin auctor libero, vitae volutpat quam suscipit sed. Nullam vulputate velit erat, sit amet molestie nibh rutrum id. Pellentesque tristique, orci sed dignissim molestie, urna libero auctor est, nec ullamcorper velit sapien rhoncus magna. Vivamus nec diam enim. Vestibulum id arcu posuere, porttitor magna et, sagittis erat. Aliquam scelerisque bibendum dui quis imperdiet. Sed tempus justo mi, vel vehicula orci luctus quis. Nam sed elit semper orci sollicitudin ultrices at eu justo. Nunc et arcu in lorem rutrum commodo imperdiet id ex. Proin auctor, ipsum vitae commodo tempus, elit magna pulvinar ex, ut lobortis enim orci mattis lorem. Etiam id elit quis magna ullamcorper sagittis id at augue. Nam non ultricies magna. Duis cursus ornare tempus. Mauris egestas dui eu ipsum mattis, at sagittis dui auctor.\",\"alignment\":\"left\"}},{\"id\":\"9-vQRWH35f\",\"type\":\"mediaPicker\",\"data\":{\"url\":\"http://localhost:8001/uploads/media/image/test-form-media.jpg\",\"caption\":\"\",\"mediaType\":\"image\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsContent\"}', '[]', 'Comment poster un article sur ce blog', 'Cet article vous permettra de comprendre comment poster un article sur ce site', 'comment-poster-un-article-sur-ce-blog', 18, 1, 4, '/blog/frontend-dev/comment-poster-un-article-sur-ce-blog', 25, 1),
(36, '2021-04-25 22:30:03', NULL, 'test form update handle', '{\"time\":1620046863678,\"blocks\":[{\"id\":\"Oc1mVafOu_\",\"type\":\"paragraph\",\"data\":{\"text\":\"aaaa\",\"alignment\":\"left\"}},{\"id\":\"y4xZz9x8_y\",\"type\":\"mediaPicker\",\"data\":{\"url\":\"http://localhost:8001/uploads/media/image/test-ajax.png\",\"caption\":\"\",\"mediaType\":\"image\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsChapo\"}', '{\"time\":1620046863681,\"blocks\":[{\"id\":\"cnQazxHMQi\",\"type\":\"paragraph\",\"data\":{\"text\":\"aaa\",\"alignment\":\"left\"}},{\"id\":\"CDKNYZty3J\",\"type\":\"image\",\"data\":{\"url\":\"https://images.bfmtv.com/a6P0_hocjFsdmYaZv2V3Xpgktaw=/0x36:768x468/768x0/images/A-la-terrasse-dun-restaurant-a-Paris-le-6-octobre-2020-496747.jpg\",\"caption\":\"\",\"withBorder\":false,\"withBackground\":false,\"stretched\":false}}],\"version\":\"2.21.0\",\"id\":\"editorjsContent\"}', '[]', 'aaaa', 'bb', 'test-form-update-handle', 18, 1, 4, '/blog/frontend-dev/test-form-update-handle', 26, 1),
(37, '2021-05-03 15:47:22', '2021-05-13 02:08:53', 'Recette : Nouilles dandan', '{\"time\":1620991312178,\"blocks\":[{\"id\":\"mwTeAJe8YF\",\"type\":\"paragraph\",\"data\":{\"text\":\"Voici la recette du plat de nouilles dandan\",\"alignment\":\"left\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsChapo\"}', '{\"time\":1620991312195,\"blocks\":[{\"id\":\"Ny7eh7KST8\",\"type\":\"header\",\"data\":{\"text\":\"INGRÉDIENTS\",\"level\":2},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"P1GTZCoaYD\",\"type\":\"paragraph\",\"data\":{\"text\":\"Un paquet de nouilles Dandan ou autres nouilles de blé chinoises (environ 250 g)\",\"alignment\":\"left\"}},{\"id\":\"UZkSbQ48ZB\",\"type\":\"header\",\"data\":{\"text\":\"SAUCE\",\"level\":3},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"X8vptNgO6N\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"items\":[\"1 ½ c. à soupe pâte de sésame chinoise ou tahini\",\"1 c. à soupe sauce soya claire\",\"1 c. à soupe sauce soya foncée\",\"2 c. à thé vinaigre noir chinois*\",\"2 c. à soupe huile (idéalement de l\'huile épicée)\",\"1 c. à thé&nbsp;poivre de Sichuan vert&nbsp;ou&nbsp;poivre de Sichuan rouge, moulu\"]}},{\"id\":\"YXPlPlBTj-\",\"type\":\"header\",\"data\":{\"text\":\"PORC\",\"level\":3},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"WnIfYKmV3L\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"items\":[\"huile\",\"3&nbsp;piments&nbsp;secs\",\"100 g porc haché\",\"3 c. à soupe zha cai (tiges de moutarde fermentées) ou 2 c. à soupe légumes fermentés de Tianjin, hachés*\",\"1 c. à thé vin de riz\",\"1 c. à thé sauce soya\",\"sel\"]}},{\"id\":\"kVMOVKYAmT\",\"type\":\"header\",\"data\":{\"text\":\"GARNITURES\",\"level\":3},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"mcvVpHeTX0\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"items\":[\"1 oignon vert, haché finement\",\"¼ tasse arachides grillées, hachées (opt.)\",\"1 c. à soupe graines de sésame (opt.)\",\"1 c. à thé ail frit (opt.)\"]}},{\"id\":\"oGjLDnNZSI\",\"type\":\"mediaPicker\",\"data\":{\"url\":\"http://localhost:8001/uploads/media/image/test-nouvelle-image.jpg\",\"caption\":\"\",\"mediaType\":\"image\"}},{\"id\":\"xVG2aHil3n\",\"type\":\"header\",\"data\":{\"text\":\"PRÉPARATION\",\"level\":2},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"UsGMwnenzG\",\"type\":\"list\",\"data\":{\"style\":\"ordered\",\"items\":[\"Mettre tout les ingrédients de la sauce dans un bol et bien mélanger. Réserver.\",\"Cuire les nouilles à l\'eau bouillante.\",\"Entretemps, chauffer l\'huile dans un wok à feu vif. Ajouter les piments et frire quelques secondes. Ajouter le porc et cuire en le défaisant à la spatule, jusqu\'à ce qu\'il brunisse.\",\"Ajouter les légumes fermentés et bien mélanger. Ajouter le vin de riz, la sauce soya et le sel. Cuire quelques secondes de plus, jusqu\'à ce que le liquide soit presque complètement évaporé. Réserver.\",\"Égoutter les nouilles en réservant un peu de l\'eau de cuisson dans un bol.\",\"Mettre les nouilles dans un grand bol, ajouter la sauce et l\'eau de cuisson. Mélanger pour que les nouilles soient bien couvertes de sauce.\",\"Placer les nouilles dans des bols ou assiettes de service, ajouter le porc et terminer avec les garnitures.\"]}}],\"version\":\"2.21.0\",\"id\":\"editorjsContent\"}', '[]', 'Nouilles dandan', 'Recette du plat sichuanais de nouilles dandan', 'recette-nouilles-dandan', 18, 1, 8, '/blog/culinaire/recette-nouilles-dandan', 27, 1);

-- --------------------------------------------------------

--
-- Table structure for table `post_media`
--

CREATE TABLE `post_media` (
  `id` int NOT NULL,
  `post_id` int NOT NULL,
  `media_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `post_tag`
--

CREATE TABLE `post_tag` (
  `id` int NOT NULL,
  `tag_id` int NOT NULL,
  `post_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `roles` json NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `presentation` varchar(255) DEFAULT NULL,
  `username` varchar(45) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `last_connexion` datetime DEFAULT NULL,
  `media_id` int DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `uuid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `created_at`, `updated_at`, `full_name`, `status`, `roles`, `email`, `password`, `presentation`, `username`, `token`, `last_connexion`, `media_id`, `slug`, `path`, `uuid`) VALUES
(1, '2021-03-02 07:54:09', NULL, 'Jean', 1, '[\"ROLE_USER\"]', 'jeanrene@gmail.com', '$2y$10$oVUddUqU2vN.CbCNhMkor.m1FnrWkgMmrDFzMy08w0.TPmuVpR/b6', 'Bonjour je m\'appelle Jean René', 'Jeanrene91', NULL, '2021-04-26 12:33:16', NULL, 'jeanrene91', '/membres/jeanrene91', '845dacff-74af-4eb4-8d6d-339fe786ec93'),
(2, '2021-03-05 08:10:26', NULL, 'Jeanette', 1, '[\"ROLE_USER\"]', 'jeanette@gmail.com', '$2y$10$ClWdjcZq4BquE/dzixqZwOdw5Ja0zo/onZRL8N6y6loHnBZAz/ZFy', 'Bonjour moi c\'est Jeanette', 'Jeanettedu75', NULL, '2021-03-09 13:30:39', 10, 'jeanettedu75', '/membres/jeanettedu75', '4fbd9616-347d-4e03-8836-9d472fd686ec'),
(18, '2021-04-02 12:21:08', '2021-04-02 12:21:28', 'Admin User', 1, '[\"ROLE_ADMIN\", \"ROLE_USER\"]', 'admin@admin.com', '$2y$10$pqrdU6axfEecQOoqwUD/L.81weKWqBm9zjCppX4sFGhSY8eT89GLG', '', 'admin', '14dc9765-698b-46a8-85b5-3d943bd670b7', '2021-05-14 17:42:18', 24, 'admin', '/membres/admin', '0cc060ab-3807-4892-942e-cd0a2d25bb34');

-- --------------------------------------------------------

--
-- Table structure for table `user_media`
--

CREATE TABLE `user_media` (
  `id` int NOT NULL,
  `media_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD UNIQUE KEY `slug_UNIQUE` (`slug`),
  ADD UNIQUE KEY `path_UNIQUE` (`path`),
  ADD KEY `fk_category_media1_idx` (`media_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`,`user_id`,`post_id`),
  ADD KEY `fk_comment_user1_idx` (`user_id`),
  ADD KEY `fk_comment_post1_idx` (`post_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`,`media_type_id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD UNIQUE KEY `slug_UNIQUE` (`slug`),
  ADD UNIQUE KEY `path_UNIQUE` (`path`),
  ADD KEY `fk_media_media_type1_idx` (`media_type_id`);

--
-- Indexes for table `media_type`
--
ALTER TABLE `media_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD UNIQUE KEY `slug_UNIQUE` (`slug`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`,`user_id`),
  ADD UNIQUE KEY `path_UNIQUE` (`path`),
  ADD UNIQUE KEY `slug_UNIQUE` (`slug`),
  ADD KEY `fk_post_user_idx` (`user_id`),
  ADD KEY `fk_post_category1_idx` (`category_id`),
  ADD KEY `fk_post_media1_idx` (`media_id`);

--
-- Indexes for table `post_media`
--
ALTER TABLE `post_media`
  ADD PRIMARY KEY (`id`,`post_id`,`media_id`),
  ADD KEY `fk_post_media_post1_idx` (`post_id`),
  ADD KEY `fk_post_media_media1_idx` (`media_id`);

--
-- Indexes for table `post_tag`
--
ALTER TABLE `post_tag`
  ADD PRIMARY KEY (`id`,`tag_id`,`post_id`),
  ADD KEY `fk_post_tag_tag1_idx` (`tag_id`),
  ADD KEY `fk_post_tag_post1_idx` (`post_id`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD UNIQUE KEY `uuid_UNIQUE` (`uuid`),
  ADD UNIQUE KEY `path_UNIQUE` (`path`),
  ADD UNIQUE KEY `slug_UNIQUE` (`slug`),
  ADD KEY `fk_user_media1_idx` (`media_id`);

--
-- Indexes for table `user_media`
--
ALTER TABLE `user_media`
  ADD PRIMARY KEY (`id`,`media_id`,`user_id`),
  ADD KEY `fk_post_media_media1_idx` (`media_id`),
  ADD KEY `fk_user_media_user1_idx` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `media_type`
--
ALTER TABLE `media_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `post_media`
--
ALTER TABLE `post_media`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_tag`
--
ALTER TABLE `post_tag`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_media`
--
ALTER TABLE `user_media`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `fk_category_media1` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_post1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `fk_comment_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `fk_media_media_type1` FOREIGN KEY (`media_type_id`) REFERENCES `media_type` (`id`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_post_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `fk_post_media1` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_post_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `post_media`
--
ALTER TABLE `post_media`
  ADD CONSTRAINT `fk_post_media_media1` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `fk_post_media_post1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`);

--
-- Constraints for table `post_tag`
--
ALTER TABLE `post_tag`
  ADD CONSTRAINT `fk_post_tag_post1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `fk_post_tag_tag1` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_media1` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;

--
-- Constraints for table `user_media`
--
ALTER TABLE `user_media`
  ADD CONSTRAINT `fk_post_media_media10` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`),
  ADD CONSTRAINT `fk_user_media_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
