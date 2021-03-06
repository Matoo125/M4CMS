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
  content_delta TEXT DEFAULT NULL,
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
  content_delta TEXT DEFAULT NULL,
  tags TEXT DEFAULT NULL,
  author_id INT NOT NULL ,
  image_id INT DEFAULT NULL ,
  category_id INT DEFAULT NULL,
  page_id INT DEFAULT NULL,
  is_published TINYINT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE media (
  id INT AUTO_INCREMENT PRIMARY KEY,
  folder VARCHAR(40) DEFAULT NULL,
  filename VARCHAR(255) NOT NULL,
  alt VARCHAR(255),
  type VARCHAR(145) NOT NULL,
  size INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE gallery (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title varchar(255) NOT NULL,
  description text,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE matcher (
  id INT AUTO_INCREMENT PRIMARY KEY,
  table1 varchar(255) NOT NULL,
  table2 varchar(255) NOT NULL,
  id1 INT NOT NULL,
  id2 INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(60) NOT NULL,
  value TEXT DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE plugins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(40) DEFAULT NULL,
  active TINYINT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  author_id INT(11) DEFAULT 0,
  content TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);