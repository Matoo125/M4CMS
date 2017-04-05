-- --------------------------------
-- SQL sample database
-- --------------------------------

--
-- Table structure
--

-- users table
CREATE TABLE users(
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(40) DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  first_name VARCHAR(40) DEFAULT NULL,
  last_name VARCHAR(40) DEFAULT NULL,
  about_me TEXT DEFAULT NULL,
  email VARCHAR(255) NOT NULL,
  role INT(1) NOT NULL,
  image_id VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- pages table
CREATE TABLE pages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(128) NOT NULL,
  description TEXT DEFAULT NULL,
  content TEXT DEFAULT NULL,
  image_id INT NOT NULL ,
  is_published TINYINT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  page_id INT NOT NULL,
  image_id INT NOT NULL ,
  title VARCHAR(128),
  description TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)

-- posts table
CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(128) NOT NULL,
  description TEXT DEFAULT NULL,
  content TEXT DEFAULT NULL,
  tags TEXT DEFAULT NULL,
  author_id INT NOT NULL ,
  image_id INT NOT NULL ,
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
  type VARCHAR(14) NOT NULL ,
  size INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);

-- page_data table
CREATE TABLE page_data (
  id INT AUTO_INCREMENT PRIMARY KEY,
  data_title VARCHAR(255) NOT NULL,
  data_content TEXT DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- insert data
INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `about_me`, `email`,`role`, `image`, `created_at`, `updated_at`) VALUES (1, 'M4', '$2y$10$ZdHYo1dv/RXS.Zj68oF3Je4IDC6O.oUwyOaLlO8uish9pXjCwPlbG', 'Matej', 'Vrzala', 'developer', 'vrzala.matej@gmail.com', 'admin', 'default.jpg', '2017-02-11 09:00:21', '2017-02-11 09:00:21')
