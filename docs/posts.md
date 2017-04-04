---
currentMenu: posts
---

# Posts

Posts are the best way to create dynamic content for the website. There can be much more posts than pages and every post is assigned to some page category. Post can be also assigned to page directly without selecting category.

| COLUMN        | TYPE           | DEFAULT             | EXTRA |
|:------------- |:------------- |:---------            |:------------
| id            | int           | AUTO_INCREMENT       |  PRIMARY KEY
| title         | varchar(128)  |   NOT NULL           | ---
| description   | TEXT          |    NULL              | ---
| content       | TEXT          |    NULL              | ---
| tags          | TEXT          |    NULL              | ---
| author_id     | TEXT          |    NULL              | ---
| image_id      | TEXT          |    NULL              | ---
| category_id   | TEXT          |    NULL              | ---
| page_id       | TEXT          |    NULL              | ---
| is_published  | TINYINT       |    0                 | ---
| created_at    | TIMESTAMP     |    CURRENT_TIMESTAMP | ---
| updated_at    | TIMESTAMP     |    CURRENT_TIMESTAMP |  ON UPDATE CURRENT_TIMESTAMP
