---
currentMenu: pages
---

# Pages

Pages have their own content and can also contain categories with different posts assigned to them. Every page has to have title to be created. Everything else is optional.

| COLUMN        | TYPE           | DEFAULT             | EXTRA |
|:------------- |:------------- |:---------            |:------------
| id            | int           | AUTO_INCREMENT       |  PRIMARY KEY
| title         | varchar(128)  |   NOT NULL           | ---
| description   | TEXT          |    NULL              | ---
| content       | TEXT          |    NULL              | ---
| is_published  | TINYINT       |    0                 | ---
| created_at    | TIMESTAMP     |    CURRENT_TIMESTAMP | ---
| updated_at    | TIMESTAMP     |    CURRENT_TIMESTAMP |  ON UPDATE CURRENT_TIMESTAMP
