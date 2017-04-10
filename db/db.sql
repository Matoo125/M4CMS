-- --------------------------------
-- SQL sample database
-- --------------------------------
DROP DATABASE IF EXISTS m4cms;
CREATE DATABASE m4cms;
USE m4cms;

--
-- Table structure
--

-- users table
CREATE TABLE users(
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(40) DEFAULT NULL,
  slug VARCHAR(128) NOT NULL,
  password VARCHAR(255) NOT NULL,
  first_name VARCHAR(40) DEFAULT NULL,
  last_name VARCHAR(40) DEFAULT NULL,
  about_me TEXT DEFAULT NULL,
  email VARCHAR(255) NOT NULL,
  role INT(1) NOT NULL,
  image_id int(11) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- pages table
CREATE TABLE pages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(128) NOT NULL,
  slug VARCHAR(128) NOT NULL,
  description TEXT DEFAULT NULL,
  content TEXT DEFAULT NULL,
  image_id INT DEFAULT NULL ,
  is_published TINYINT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  page_id INT NOT NULL,
  image_id INT DEFAULT NULL ,
  title VARCHAR(128),
  slug VARCHAR(128) NOT NULL,
  description TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- posts table
CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(128) NOT NULL,
  slug VARCHAR(128) NOT NULL,
  description TEXT DEFAULT NULL,
  content TEXT DEFAULT NULL,
  tags TEXT DEFAULT NULL,
  author_id INT NOT NULL ,
  image_id INT DEFAULT NULL ,
  category_id INT DEFAULT NULL,
  page_id INT DEFAULT NULL,
  is_published TINYINT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE images(
  id INT AUTO_INCREMENT PRIMARY KEY,
  folder VARCHAR(40) DEFAULT NULL,
  name VARCHAR(128) NOT NULL,
  alt VARCHAR(255),
  type VARCHAR(14) NOT NULL ,
  size INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);


-- insert data
INSERT INTO `users` (`id`, `username`, `slug`, `password`, `first_name`, `last_name`, `about_me`, `email`, `role`, `image_id`, `created_at`, `updated_at`) VALUES (1, 'M4', 'm4', '$2y$10$ZdHYo1dv/RXS.Zj68oF3Je4IDC6O.oUwyOaLlO8uish9pXjCwPlbG', 'Matej', 'Vrzala', 'developer', 'vrzala.matej@gmail.com', '4', NULL, '2017-02-11 09:00:21', '2017-02-11 09:00:21');

INSERT INTO `images` (`id`, `folder`, `name`, `type`, `size`, `created_at`, `updated_at`) VALUES
(7,	'pages',	'47044774-image.jpg',	'image/jpeg',	130279,	'2017-04-10 07:41:21',	'2017-04-10 07:41:21');


INSERT INTO `pages` (`id`, `title`, `slug`, `description`, `content`, `image_id`, `is_published`, `created_at`, `updated_at`) VALUES
(4,	'First Page', 'first-page',	'this is short descriptions',	'<p>Lorem ipsum dolor sit amet, arcu ac. Mauris nulla platea sed, interdum nostra lectus, amet enim eget, scelerisque mollis non excepteur eget. Ligula nec ligula pede enim, nullam ut non id, semper pellentesque vivamus, nonummy malesuada consequatur. Bibendum elementum arcu massa odio sagittis libero, nulla fringilla leo, nullam velit nibh. Animi per adipiscing iaculis ligula ipsum elementum, diam sed viverra mauris orci nonummy, eget mi torquent sit vestibulum magna, massa curabitur mauris id, modi curabitur vel luctus quis urna. Arcu mauris imperdiet a nunc tristique, lectus euismod lectus at et nascetur. Suspendisse sapien, ut id viverra justo, ultricies lacus duis maecenas volutpat tincidunt, mauris odio dis. Ut et cum id imperdiet. Mi ligula urna duis interdum sit, cursus lorem congue vulputate, diam lectus saepe enim sem velit. Nulla in mattis felis vehicula eget.</p><p>Odio parturient lacus nulla et pede. Ipsum sit senectus suscipit, rhoncus luctus, velit morbi quisque ultrices morbi elit. Ipsum aut dui consequat quis sollicitudin, magna erat augue adipiscing, integer mollis curabitur duis. Morbi id libero sed, elit placerat libero eget justo velit, consequat neque neque eleifend ridiculus auctor. Ut risus vivamus turpis eu, sed blandit, lorem fusce consequat fames nisl faucibus pede, in nunc vel accusamus. Massa a adipiscing, consectetuer vivamus, tellus a id sed, suspendisse mauris quis condimentum cubilia ac. Lacus neque eu dignissim blanditiis, habitasse nonummy scelerisque, risus hendrerit eu porta pharetra mauris wisi.</p><p>Dignissim facilisis elit lorem, blandit urna turpis tellus porro at ut, massa vitae odio malesuada viverra. Eu ut, accumsan nisl posuere placerat lorem convallis a, dictum odio purus mattis proin. Sem mauris rhoncus, dolor purus ut. Eget adipiscing, magna imperdiet, leo velit, lorem elit dictum sit sed amet. Sed et at eget sed vestibulum amet. Ante suspendisse.</p><p>Sollicitudin ac, molestie nullam dolor, in accumsan enim i</p>',	7,	1,	'2017-04-10 07:41:21',	'2017-04-10 07:41:21');
