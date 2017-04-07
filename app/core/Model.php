<?php

namespace app\core;

/*
 * Abstract Core Class Model
 * is extended by all the other models.
 * Talks with MySQL Database
 */

use app\config\Database;

abstract class Model
{
    // Database connection
    // used by every sql request
    // @return $db connection
    protected static function getDB()
    {
        static $db = null;
        // create connection
        // only if there is not
        // already
        if ($db === null) {
            try {
                $dns = 'mysql:host=' . Database::HOST . ';dbname=' . Database::NAME . ';charset=utf8';
                $db = new \PDO($dns, Database::USER, Database::PASSWORD);
                $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                return $db;
            } catch (\PDOException $e) {
                echo $e->getMessage();
                return null;
            }
        }

        return $db;
    }

    public function insert($query, $params)
    {

    }

    public function update($query, $params)
    {
      return self::runQuery($query, $params, 3);
    }

    public function fetch($query, $params)
    {
      return self::runQuery($query, $params, 1);
    }

    public function fetchAll($query, $params)
    {
      return self::runQuery($query, $params, 2);
    }

    public static function runQuery($query, $params, $type)
    {
      $stmt = self::getDb()->prepare($query);
      $stmt->execute($params);

      switch ($type) {
        case 2:
          if ($results = $stmt->fetchAll()) {
             return $results;
          }
          break;
        case 1:
          if ($result = $stmt->fetch()) {
            return $result;
          }
          break;
        case 3:
          return $stmt->rowCount() ? true : false;
          break;
      }
    }


    /*
     * Count rows in table
     * @param $where adds where clause to query
     * @param $like adds like clause to query
     * @return number of rows
     */
    public function countTable($table, $where = null, $like = null) {

        $db = self::getDB();

        $sql = "SELECT count(*) FROM " . $table;
        if ($where) $sql .= " WHERE " . $where;
        if ($like) $sql .= " LIKE '" . $like . "'";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? $result[0] : null;
    }
}
