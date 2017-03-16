<?php

namespace app\core;

/*
 * Core Model
 * is extended by other models
 */

 use app\config\Database;

abstract class Model
{
    protected static function getDB()
    {
        static $db = null;

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
