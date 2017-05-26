# app/core/Model.php 

[Abstract model class](https://github.com/Matoo125/M4CMS/blob/master/app/core/Model.php) is intended to be extended by all the other models (which should be stored in `app/models` folder).  

### Parameters

| Name  | Description                 |
| ----- | --------------------------- |
| query | Holds Query Helper Instance |
| db    | Holds database connection   |

### Talking With Database

There are 4 important helper methods to run SQL queries. 

1. save  - insert / update / delete record

   > Save method has third optional argument `lastInsertedId` which if set to `true` returns last inserted id. Otherwise it returns boolean of success.

2. fetch - get single row

3. fetchAll - get array of results

4. runQuery - run the query

> First 3 of them are just extending runQuery for simpler use. All of them take first 2 arguments **SQL Query** and **Array of Parameters**. 

If you want to use runQuery directly you need to set third parameter **type** [integer]. 

| type | description                              |
| ---- | ---------------------------------------- |
| 1    | Fetch All [Assoc]                        |
| 2    | Fetch [Assoc]                            |
| 3    | Create Update Delete returns boolean     |
| 4    | Create Update Delete returns last inserted id |

### Other core methods

**image** (received image *, folder to upload *)

This method is storing image records in `images table` and it also calls `Image::upload` helper to upload the image.

It returns last inserted id or null if it fails.

------

**countTable** (table to count *, where clause, like clause)

This method counts number of records from table and you can use where or like clause to filter the results.

Returns number of rows or null.