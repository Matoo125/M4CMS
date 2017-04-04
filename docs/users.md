---
currentMenu: users
---

# Users

There are multiple type of users on this page.


| number   | word           | description         |
|:-----    |:----           |:----                |
| 0        | not registered | can browse website  |
| 1        | User           | can suggest changes |
| 2        | Author         | can create content  |
| 3        | Admin          | can do everything   |

Table design

| COLUMN        | TYPE           | DEFAULT             | EXTRA |
|:------------- |:------------- |:---------            |:------------
| id            | int(11)       | AUTO_INCREMENT       |  PRIMARY KEY
| username      | varchar(40)   |   NOT NULL           | ---
| email         | varchar(255)  |    NOT NULL          |  ---
| password      | varchar(255)  |    NOT NULL          | ---
| first_name    | varchar(40)   |    NULL              | ---
| last_name     | varchar(40)   |    NULL              | ---
| about_me      | TEXT          |    NULL              | ---
| role          | int(1)        |    NOT NULL          |  ---
| image_id      | int(11)       |    NULL              |  ---
| created_at    | TIMESTAMP     |    CURRENT_TIMESTAMP | ---
| updated_at    | TIMESTAMP     |    CURRENT_TIMESTAMP |  ON UPDATE CURRENT_TIMESTAMP
