-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 18, 2021 at 10:11 AM
-- Server version: 8.0.23
-- PHP Version: 7.4.3

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
(27, '2021-05-14 13:21:16', '2021-05-14 13:21:16', 'dandan-2', 'dandan-2', '/uploads/media/image/dandan-2.jpg', 'b16f1f15-8f12-4e5d-9b85-2efb9d677c68', 1, 2, 'dandan-2'),
(29, '2021-05-17 11:57:18', '2021-05-17 11:57:18', 'inferno', 'inferno', '\\uploads/media/image\\inferno.jpg', '65d84e48-8f69-4f01-b63f-583dd447264e', 1, 2, 'inferno'),
(30, '2021-05-17 12:03:08', '2021-05-17 12:03:08', 'Cozy feeling', 'Cozy feeling', '\\uploads/media/image\\cozy-feeling.png', '0e914de2-d9db-44ee-ac72-ec25fd8ec7b1', 1, 2, 'cozy-feeling');

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
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
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
(30, '2021-04-13 11:53:03', '2021-04-16 04:06:24', 'nouveau post', '{\"time\":1621253249543,\"blocks\":[{\"id\":\"_1bSFNBCFH\",\"type\":\"header\",\"data\":{\"text\":\"Titre\",\"level\":2},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}}],\"version\":\"2.21.0\",\"id\":\"editorjsChapo\"}', '{\"time\":1621253249544,\"blocks\":[{\"id\":\"oDd3JIz5bY\",\"type\":\"header\",\"data\":{\"text\":\"Titre du contenu&nbsp;\",\"level\":1},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}}],\"version\":\"2.21.0\",\"id\":\"editorjsContent\"}', '[]', 'taaa', 'mlvjsmvlsj', 'nouveau-post', 18, 1, 3, '/blog/backend-dev/nouveau-post', NULL, 0),
(31, '2021-04-16 05:02:18', '2021-04-17 20:19:47', 'test article', '{\"time\":1621253183673,\"blocks\":[{\"id\":\"3TcEgArU4h\",\"type\":\"header\",\"data\":{\"text\":\"Titre test\",\"level\":2},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"iFNJxGX2BM\",\"type\":\"mediaPicker\",\"data\":{\"url\":\"http://mvc-oc.test/uploads/media/image/inferno.jpg\",\"caption\":\"inferno\",\"mediaType\":\"image\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsChapo\"}', '{\"time\":1621253183674,\"blocks\":[{\"id\":\"uvV3bG8vRc\",\"type\":\"mediaPicker\",\"data\":{\"url\":\"http://mvc-oc.test/uploads/media/image/cozy-feeling.png\",\"caption\":\"cozy feeling\",\"mediaType\":\"image\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsContent\"}', '[]', 'aaa', 'ccc', 'test-article', 18, 1, 3, '/blog/backend-dev/test-article', NULL, 0),
(32, '2021-04-19 11:40:51', '2021-05-14 13:13:12', 'Mon premier prototype sous Unity', '{\"time\":1620994060232,\"blocks\":[{\"id\":\"4b_uqvnWOU\",\"type\":\"paragraph\",\"data\":{\"text\":\"Mon premier prototype sous Unity, un jeu en 2D topdown\",\"alignment\":\"left\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsChapo\"}', '{\"time\":1620994060236,\"blocks\":[{\"id\":\"Ji5OuZvIN_\",\"type\":\"header\",\"data\":{\"text\":\"Prémices du projet\",\"level\":2},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}}],\"version\":\"2.21.0\",\"id\":\"editorjsContent\"}', '[]', 'premier prototype', 'Mon premier prototype sous Unity, un jeu en 2D topdown', 'mon-premier-prototype-sous-unity', 18, 1, 5, '/blog/game-dev/mon-premier-prototype-sous-unity', 22, 0),
(34, '2021-04-24 01:33:39', '2021-05-14 14:08:39', 'Comment prévenir une attaque xss', '{\"time\":1620994359460,\"blocks\":[{\"id\":\"0vzUGyyxgq\",\"type\":\"paragraph\",\"data\":{\"text\":\"Vous voulez savoir comment les formulaires de ce site protègent d\'une attaque xss ? C\'est par ici\",\"alignment\":\"left\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsChapo\"}', '{\"time\":1620994359464,\"blocks\":[{\"id\":\"l3jF3urKVx\",\"type\":\"header\",\"data\":{\"text\":\"Champs cachés\",\"level\":2},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"gsA8HK3Qbt\",\"type\":\"paragraph\",\"data\":{\"text\":\"Ce site utilise un champ caché avec un nouveau token CSRF généré à chaque requête, ainsi une tentative de cross site scripting serait infructueuse car le CSRF serait inconnu de l\'attaquant.\",\"alignment\":\"left\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsContent\"}', '[]', 'prévention attaque xss', 'Comment prévenir une attaque xss, description de la manière de faire de ce blog', 'comment-prevenir-une-attaque-xss', 18, 1, 4, '/blog/frontend-dev/comment-prevenir-une-attaque-xss', 24, 0),
(35, '2021-04-25 15:59:06', '2021-04-29 19:08:17', 'Comment poster un article sur ce blog', '{\"time\":1621328055982,\"blocks\":[{\"id\":\"oNbFdSKV1L\",\"type\":\"header\",\"data\":{\"text\":\"Titre du chapo\",\"level\":1},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"tfHTQjflm6\",\"type\":\"paragraph\",\"data\":{\"text\":\"Text\",\"alignment\":\"left\"}},{\"id\":\"c0GEDoT__8\",\"type\":\"paragraph\",\"data\":{\"text\":\"Lorem ipsum dolor sit amet, <a href=\\\"https://google.com\\\">consectetur</a> adipiscing elit. Fusce nec magna quis odio consequat semper sed elementum lorem. Aenean massa lorem, lacinia a nunc quis, consequat luctus libero. Fusce gravida facilisis dignissim. Suspendisse dapibus leo eu nibh pellentesque, rutrum efficitur nunc molestie. Ut scelerisque, metus non facilisis sagittis, nisl quam ultricies elit, in efficitur mauris ante nec urna. Aliquam sodales metus finibus, finibus nibh vel, interdum sapien. Curabitur vulputate eu tortor id finibus. Fusce quis est gravida, laoreet urna id, sollicitudin enim. Mauris hendrerit vestibulum erat, rutrum commodo nunc.\",\"alignment\":\"left\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsChapo\"}', '{\"time\":1621328055984,\"blocks\":[{\"id\":\"ksq3DG3kG6\",\"type\":\"header\",\"data\":{\"text\":\"Contenu de l\'article\",\"level\":2},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"3X3XEVKdBs\",\"type\":\"paragraph\",\"data\":{\"text\":\"Suspendisse rutrum dui ac suscipit convallis. Curabitur ut dui sed nulla maximus vehicula. Etiam mattis posuere est ut ultricies. Suspendisse convallis semper ex non semper. Morbi fringilla facilisis varius. Mauris pellentesque volutpat convallis. Nunc vel dolor ligula. Nam libero metus, cursus nec felis et, laoreet vulputate elit. Proin ultrices ultricies elit eget malesuada. Praesent pulvinar ipsum purus, nec posuere sem vulputate sit amet. Maecenas in dapibus urna, ut eleifend purus. Pellentesque mollis magna tellus, vitae suscipit velit tempor quis. Maecenas non nisi consectetur urna dictum suscipit. In a nisl at libero rhoncus placerat quis quis mi. Vestibulum a porttitor ipsum.\",\"alignment\":\"left\"}},{\"id\":\"W49dJmLE6p\",\"type\":\"paragraph\",\"data\":{\"text\":\"Aliquam dui risus, volutpat sed convallis ut, eleifend ac nisi. Proin consequat ante ut nunc rutrum, sed lacinia ante imperdiet. Nulla non mattis eros, vitae mattis lacus. Donec sed purus suscipit, auctor nisl at, euismod felis. Curabitur aliquet nisl non vehicula ullamcorper. Ut gravida metus sit amet metus aliquam, ut porta libero semper. Sed placerat iaculis ipsum, eget malesuada ipsum fermentum nec.\",\"alignment\":\"left\"}},{\"id\":\"BEmWa0nntJ\",\"type\":\"code\",\"data\":{\"code\":\"$postRepository = new PostRepository($this->getManager());\\n        $commentRepository = new CommentRepository($this->getManager());\\n        $categoryRepository = new CategoryRepository($this->getManager());\\n        $comment = new Comment();\\n        $commentForm = new CommentForm($request,$comment, $this->session, [\'name\' => \'commentForm\', \'wrapperClass\' => \'mb-1\']);\\n        $blogManager = new BlogManager($this->getManager(), $postRepository);\\n        $postData = $this->getManager()->getEntityData(\'post\');\\n        $post = $postRepository->findOneBy(\'slug\', $slug);\\n        $content[\'post\'] = $blogManager->hydratePost($post, $postData);\\n        $content[\'comments\'] = $commentRepository->findBy(\'post_id\', $post->getId(), \'created_at\', \'DESC\');\\n        $content[\'categories\'] = $categoryRepository->findAll();\\n\\n        $commentForm->handle($request);\\n\\n        if ($commentForm->isSubmitted) {\\n            if ($commentForm->isValid) {\\n                $comment->setContent($commentForm->getData(\'Commentaire\'));\\n                if (true === $blogManager->saveComment($comment, $post, $this->getUser())) {\\n                    $this->redirect($post->getPath(), [\'type\' => \'success\', \'message\' => \\\"Commentaire en attente de validation\\\"]);\\n                }\\n            }\\n\\n            $this->redirect($post->getPath(), [\'type\' => \'danger\', \'message\' => \\\"Une erreur s\'est produite, le commentaire n\'a pas pu être enregistré\\\"]);\\n        }\\n\\n            return $this->render(\'blog/show.html.twig\', [\\n            \'content\' => $content,\\n            \'form\' => $commentForm->renderForm()\\n        ]);\\n    }\",\"languageCode\":\"php\"}},{\"id\":\"3Zo5wDq13a\",\"type\":\"paragraph\",\"data\":{\"text\":\"Nullam sodales, purus quis condimentum commodo, nunc dui dignissim lacus, vel aliquet metus elit ac sem. Etiam elit velit, consectetur eget vestibulum sed, efficitur nec tellus. Morbi euismod nunc in dolor rutrum blandit. In auctor dignissim magna id aliquet. Praesent blandit urna arcu, ut maximus elit fringilla non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Etiam laoreet libero nec aliquam eleifend. Quisque ornare convallis metus, in condimentum nibh gravida non. Duis vitae arcu quis metus hendrerit convallis ut id nunc. Interdum et malesuada fames ac ante ipsum primis in faucibus.\",\"alignment\":\"left\"}},{\"id\":\"2SO2hlQZ55\",\"type\":\"header\",\"data\":{\"text\":\"Titre 2 de l\'article\",\"level\":2},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"nZjzXYlxFK\",\"type\":\"paragraph\",\"data\":{\"text\":\"Suspendisse tellus sem, iaculis eu risus in, molestie sodales orci. Aenean accumsan odio risus, in accumsan nisl vestibulum tincidunt. Pellentesque interdum mollis eleifend. Aliquam id fermentum nibh. Donec felis velit, consequat non venenatis eget, aliquet eget justo. Cras nec odio interdum, pretium sem vel, viverra nulla. Integer sollicitudin gravida ante, id elementum ante pretium ac. Aenean accumsan venenatis tincidunt. Integer sed massa id nisi vestibulum blandit quis elementum massa. Nunc eget cursus velit, non lobortis ante. Praesent finibus tristique lacus, et posuere nulla viverra a.\",\"alignment\":\"left\"}},{\"id\":\"LrhHKUkx7S\",\"type\":\"paragraph\",\"data\":{\"text\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae lacus non est dapibus efficitur. Donec blandit dictum justo. Praesent tincidunt erat non sapien dignissim vulputate. Vivamus egestas maximus elit eget viverra. In vel facilisis turpis, nec laoreet dui. Proin eu tortor massa. Nulla facilisis neque non dui auctor lobortis. Vestibulum tellus dolor, volutpat a sapien non, scelerisque sagittis eros. Duis ac nulla et quam viverra rhoncus at non dui. Aliquam aliquam metus rutrum sem vehicula ultricies. Praesent aliquam, magna a ornare scelerisque, velit orci malesuada massa, iaculis egestas leo enim malesuada velit. Duis sit amet mi lacinia, dignissim quam ut, pellentesque elit.\",\"alignment\":\"left\"}},{\"id\":\"_bWYY8FDLi\",\"type\":\"mediaPicker\",\"data\":{\"url\":\"http://mvc-oc.test/uploads/media/image/cozy-feeling.png\",\"caption\":\"Cozy Feeling - Etienne Doux\",\"mediaType\":\"image\"}},{\"id\":\"TjIHAGF890\",\"type\":\"paragraph\",\"data\":{\"text\":\"Integer sapien ligula, molestie non felis quis, venenatis sagittis nisi. Quisque in tortor ut velit facilisis fringilla non et ligula. Proin justo lectus, egestas vitae aliquam at, feugiat vitae tellus. Vivamus semper urna ac elementum lacinia. Cras molestie dui ac elit placerat sollicitudin a a lacus. Nunc ullamcorper lacus vitae porttitor cursus. Fusce sit amet malesuada est. Fusce quis nulla et mi consectetur condimentum. Nulla volutpat neque nec dui tincidunt dignissim. Morbi mollis efficitur facilisis. Curabitur varius, turpis at aliquam congue, ligula est fringilla metus, non vestibulum libero augue ac ligula. Aliquam aliquam dolor non vehicula elementum. Ut nec neque rutrum, molestie nunc id, sollicitudin ex. Praesent eu justo accumsan, condimentum metus eget, viverra diam. Nulla facilisi.\",\"alignment\":\"left\"}},{\"id\":\"MvHQLEymGi\",\"type\":\"paragraph\",\"data\":{\"text\":\"Sed dapibus tortor eget semper venenatis. Fusce lobortis sem at diam iaculis laoreet. Nunc sollicitudin auctor libero, vitae volutpat quam suscipit sed. Nullam vulputate velit erat, sit amet molestie nibh rutrum id. Pellentesque tristique, orci sed dignissim molestie, urna libero auctor est, nec ullamcorper velit sapien rhoncus magna. Vivamus nec diam enim. Vestibulum id arcu posuere, porttitor magna et, sagittis erat. Aliquam scelerisque bibendum dui quis imperdiet. Sed tempus justo mi, vel vehicula orci luctus quis. Nam sed elit semper orci sollicitudin ultrices at eu justo. Nunc et arcu in lorem rutrum commodo imperdiet id ex. Proin auctor, ipsum vitae commodo tempus, elit magna pulvinar ex, ut lobortis enim orci mattis lorem. Etiam id elit quis magna ullamcorper sagittis id at augue. Nam non ultricies magna. Duis cursus ornare tempus. Mauris egestas dui eu ipsum mattis, at sagittis dui auctor.\",\"alignment\":\"left\"}},{\"id\":\"m-WYbvzDMg\",\"type\":\"mediaPicker\",\"data\":{\"url\":\"http://mvc-oc.test/uploads/media/image/image-pour-test-post.jpg\",\"caption\":\"Emerging - Etienne Doux\",\"mediaType\":\"image\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsContent\"}', '[]', 'Comment poster un article sur ce blog', 'Cet article vous permettra de comprendre comment poster un article sur ce site', 'comment-poster-un-article-sur-ce-blog', 18, 1, 4, '/blog/frontend-dev/comment-poster-un-article-sur-ce-blog', 25, 1),
(36, '2021-04-25 22:30:03', '2021-05-18 08:52:58', 'test form update handle', '{\"time\":1621327987569,\"blocks\":[{\"id\":\"Oc1mVafOu_\",\"type\":\"paragraph\",\"data\":{\"text\":\"aaaa\",\"alignment\":\"left\"}},{\"id\":\"y4xZz9x8_y\",\"type\":\"mediaPicker\",\"data\":{\"url\":\"http://localhost:8001/uploads/media/image/test-ajax.png\",\"caption\":\"\",\"mediaType\":\"image\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsChapo\"}', '{\"time\":1621327987569,\"blocks\":[{\"id\":\"cnQazxHMQi\",\"type\":\"paragraph\",\"data\":{\"text\":\"aaa\",\"alignment\":\"left\"}},{\"id\":\"CtpnhWsXx7\",\"type\":\"paragraph\",\"data\":{\"text\":\"test\",\"alignment\":\"left\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsContent\"}', '[]', 'aaaa', 'bb', 'test-form-update-handle', 18, 1, 4, '/blog/frontend-dev/test-form-update-handle', 26, 1),
(37, '2021-05-03 15:47:22', '2021-05-13 02:08:53', 'Recette : Nouilles dandan', '{\"time\":1621252415492,\"blocks\":[{\"id\":\"mwTeAJe8YF\",\"type\":\"paragraph\",\"data\":{\"text\":\"Voici la recette du plat de nouilles dandan\",\"alignment\":\"left\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsChapo\"}', '{\"time\":1621252415496,\"blocks\":[{\"id\":\"Ny7eh7KST8\",\"type\":\"header\",\"data\":{\"text\":\"INGRÉDIENTS\",\"level\":2},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"P1GTZCoaYD\",\"type\":\"paragraph\",\"data\":{\"text\":\"Un paquet de nouilles Dandan ou autres nouilles de blé chinoises (environ 250 g)\",\"alignment\":\"left\"}},{\"id\":\"UZkSbQ48ZB\",\"type\":\"header\",\"data\":{\"text\":\"SAUCE\",\"level\":3},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"X8vptNgO6N\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"items\":[\"1 ½ c. à soupe pâte de sésame chinoise ou tahini\",\"1 c. à soupe sauce soya claire\",\"1 c. à soupe sauce soya foncée\",\"2 c. à thé vinaigre noir chinois*\",\"2 c. à soupe huile (idéalement de l\'huile épicée)\",\"1 c. à thé&nbsp;poivre de Sichuan vert&nbsp;ou&nbsp;poivre de Sichuan rouge, moulu\"]}},{\"id\":\"YXPlPlBTj-\",\"type\":\"header\",\"data\":{\"text\":\"PORC\",\"level\":3},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"WnIfYKmV3L\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"items\":[\"huile\",\"3&nbsp;piments&nbsp;secs\",\"100 g porc haché\",\"3 c. à soupe zha cai (tiges de moutarde fermentées) ou 2 c. à soupe légumes fermentés de Tianjin, hachés*\",\"1 c. à thé vin de riz\",\"1 c. à thé sauce soya\",\"sel\"]}},{\"id\":\"kVMOVKYAmT\",\"type\":\"header\",\"data\":{\"text\":\"GARNITURES\",\"level\":3},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"mcvVpHeTX0\",\"type\":\"list\",\"data\":{\"style\":\"unordered\",\"items\":[\"1 oignon vert, haché finement\",\"¼ tasse arachides grillées, hachées (opt.)\",\"1 c. à soupe graines de sésame (opt.)\",\"1 c. à thé ail frit (opt.)\"]}},{\"id\":\"rODEkVSsZh\",\"type\":\"mediaPicker\",\"data\":{\"url\":\"http://mvc-oc.test/uploads/media/image/test-nouvelle-image.jpg\",\"caption\":\"\",\"mediaType\":\"image\"}},{\"id\":\"xVG2aHil3n\",\"type\":\"header\",\"data\":{\"text\":\"PRÉPARATION\",\"level\":2},\"tunes\":{\"AlignmentBlockTune\":{\"alignment\":\"left\"}}},{\"id\":\"UsGMwnenzG\",\"type\":\"list\",\"data\":{\"style\":\"ordered\",\"items\":[\"Mettre tout les ingrédients de la sauce dans un bol et bien mélanger. Réserver.\",\"Cuire les nouilles à l\'eau bouillante.\",\"Entretemps, chauffer l\'huile dans un wok à feu vif. Ajouter les piments et frire quelques secondes. Ajouter le porc et cuire en le défaisant à la spatule, jusqu\'à ce qu\'il brunisse.\",\"Ajouter les légumes fermentés et bien mélanger. Ajouter le vin de riz, la sauce soya et le sel. Cuire quelques secondes de plus, jusqu\'à ce que le liquide soit presque complètement évaporé. Réserver.\",\"Égoutter les nouilles en réservant un peu de l\'eau de cuisson dans un bol.\",\"Mettre les nouilles dans un grand bol, ajouter la sauce et l\'eau de cuisson. Mélanger pour que les nouilles soient bien couvertes de sauce.\",\"Placer les nouilles dans des bols ou assiettes de service, ajouter le porc et terminer avec les garnitures.\"]}}],\"version\":\"2.21.0\",\"id\":\"editorjsContent\"}', '[]', 'Nouilles dandan', 'Recette du plat sichuanais de nouilles dandan', 'recette-nouilles-dandan', 18, 1, 8, '/blog/culinaire/recette-nouilles-dandan', 27, 1),
(38, '2021-05-17 11:58:20', '2021-05-17 11:58:20', 'Digital painting - inferno', '{\"time\":1621252739799,\"blocks\":[{\"id\":\"PGdqY42OjL\",\"type\":\"paragraph\",\"data\":{\"text\":\"Digital painting\",\"alignment\":\"left\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsChapo\"}', '{\"time\":1621252739799,\"blocks\":[{\"id\":\"jBv87jga9A\",\"type\":\"paragraph\",\"data\":{\"text\":\"Peinture faite dans Photoshop\",\"alignment\":\"left\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsContent\"}', '[]', 'Digital painting - inferno', 'Digital painting - inferno', 'digital-painting-inferno', 18, 1, 7, '/blog/digital-painting/digital-painting-inferno', 29, 0),
(39, '2021-05-17 12:02:19', '2021-05-17 12:02:19', 'Cozy feeling', '{\"time\":1621253114251,\"blocks\":[{\"id\":\"1I4WSqLPXS\",\"type\":\"paragraph\",\"data\":{\"text\":\"Cozy feeling\",\"alignment\":\"left\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsChapo\"}', '{\"time\":1621253114251,\"blocks\":[{\"id\":\"86saR0wlV-\",\"type\":\"paragraph\",\"data\":{\"text\":\"Digital painting faite dans Photoshop\",\"alignment\":\"left\"}}],\"version\":\"2.21.0\",\"id\":\"editorjsContent\"}', '[]', 'Cozy feeling', 'Cozy feeling - Etienne Doux', 'cozy-feeling', 18, 1, 7, '/blog/digital-painting/cozy-feeling', 30, 1);

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
(2, '2021-03-05 08:10:26', NULL, 'Jeanette', 1, '[\"ROLE_USER\"]', 'jeanette@gmail.com', '$2y$10$ClWdjcZq4BquE/dzixqZwOdw5Ja0zo/onZRL8N6y6loHnBZAz/ZFy', 'Bonjour moi c\'est Jeanette', 'Jeanettedu75', NULL, '2021-03-09 13:30:39', 10, 'jeanettedu75', '/membres/jeanettedu75', '4fbd9616-347d-4e03-8836-9d472fd686ec'),
(18, '2021-04-02 12:21:08', '2021-04-02 12:21:28', '', 1, '[\"ROLE_ADMIN\", \"ROLE_USER\"]', 'admin@admin.com', '$2y$10$pqrdU6axfEecQOoqwUD/L.81weKWqBm9zjCppX4sFGhSY8eT89GLG', '', 'admin', '14dc9765-698b-46a8-85b5-3d943bd670b7', '2021-05-17 12:24:07', 24, 'admin', '/membres/admin', '0cc060ab-3807-4892-942e-cd0a2d25bb34'),
(23, '2021-05-17 12:24:14', NULL, 'Etienne Doux', 1, '[\"ROLE_ADMIN\", \"ROLE_USER\"]', 'etienne.doux@gmail.com', '$2y$10$Z6zG6wdDwd6YMIQ0vyo36O4L42uobLPvZmov9JHVZSGmGNt7uHi/.', '', 'Etienne', '131bd565-022b-4c02-9ad1-1bd1b56eae2d', '2021-05-17 16:55:53', 24, 'etienne', '/membres/etienne', '8a9b112a-cb31-4962-94c3-ab7cbe826251');

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
  ADD KEY `fk_comment_post1_idx` (`post_id`),
  ADD KEY `fk_comment_user1_idx` (`user_id`) USING BTREE;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
  ADD CONSTRAINT `fk_comment_post1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`);

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
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_media1` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
