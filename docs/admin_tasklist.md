---
currentMenu: admin_tasklist
---

# Admin Tasklist

This is example plugin, which is also integrated in the application


It allows user to create complete and delete tasks in admin area

| COLUMN        | TYPE           | DEFAULT             | EXTRA |
|:------------- |:------------- |:---------            |:------------
| id            | int           | AUTO_INCREMENT       |  PRIMARY KEY
| task          | varchar(128)  |   NOT NULL           | ---
| description   | TEXT          |    NULL              | ---
| state         | TEXT          |    NULL              | ---
| created_at    | TIMESTAMP     |    CURRENT_TIMESTAMP | ---
| updated_at    | TIMESTAMP     |    CURRENT_TIMESTAMP |  ON UPDATE CURRENT_TIMESTAMP
