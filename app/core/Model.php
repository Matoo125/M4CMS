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
