---
currentMenu: categories
---

# Categories

Categories are used for separation of posts. Every page has it's own categories. There have to be page to create category.

| COLUMN        | TYPE           | DEFAULT             | EXTRA |
|:------------- |:------------- |:---------            |:------------
| id            | int           | AUTO_INCREMENT       |  PRIMARY KEY
| title         | varchar(128)  |   NOT NULL           | ---
| slug          | varchar(128)  |   NOT NULL           | ---
| description   | TEXT          |    NULL              | ---
| image_id      | TEXT          |    NULL              | ---
| page_id       | TEXT          |    NULL              | ---
| created_at    | TIMESTAMP     |    CURRENT_TIMESTAMP | ---
| updated_at    | TIMESTAMP     |    CURRENT_TIMESTAMP |  ON UPDATE CURRENT_TIMESTAMP
