<?php

namespace app\core;

/*
 * Abstract Core Class Model
 * is extended by all the other models.
 * Talks with MySQL Database
 * Written by Matej Vrzala
 * Created at January 2017
 * Last update: 10.4.2017
 */

use app\helper\Query;
use app\helper\Image;

abstract class Model
{

    protected $query;
    protected $db;

    public function __construct()
    {
      $this->query = new Query;
      $this->db = self::getDb();
    }

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
                global $config;
                $dns = 'mysql:host=' . $config['DB_HOST'] . ';dbname=' . $config['DB_NAME'] . ';charset=utf8';
                $db = new \PDO($dns, $config['DB_USER'], $config['DB_PASSWORD']);
                $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                return $db;
            } catch (\PDOException $e) {
                echo $e->getMessage();
                return null;
            }
        }

        return $db;
    }

    // To insert or update record
    // retuns boolean
    // return lastinserted id if type is 1
    public function save($query, $params, $lastInsertedId = null)
    {
      if ($lastInsertedId == 1) {
        return self::runQuery($query, $params, 4);
      }
      return self::runQuery($query, $params, 3);
    }

    // To get a single record
    public function fetch($query, $params)
    {
      return self::runQuery($query, $params, 1);
    }

    // To get multiple records
    public function fetchAll($query, $params)
    {
      return self::runQuery($query, $params, 2);
    }

    // runs query and
    // returns result
    // based on type
    public static function runQuery($query, $params, $type)
    {
      $stmt = self::getDb()->prepare($query);

      try {
        $stmt->execute($params);
      } catch (\Exception $e) {
        echo 'error: <br>';
        echo 'Query: ' . $query . "<br>";
        echo 'Params: <pre>'; print_r($params); echo '</pre>';
        echo 'Type: ' . $type . "<br>";
        echo "Exception: " . $e;
         die;


      }

      switch ($type) {
        case 2:
          if ($results = $stmt->fetchAll(\PDO::FETCH_ASSOC)) {
             return $results;
          }
          break;
        case 1:
          if ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return $result;
          }
          break;
        case 3:
          return $stmt->rowCount() ? true : false;
          break;
        case 4:
          return self::getDb()->lastInsertId();
          break;
      }
    }


    // Insert image
    // to the images table
    // return id
    public function image($image, $folder)
    {
      if ($image && Image::upload($image, $folder)) {
        $query = $this->query->insert('folder', 'name', 'type', 'size')->into('images')->build();
        $params = [
          'folder'  =>  static::$table,
          'name'    =>  $image['name'],
          'type'    =>  $image['type'],
          'size'    =>  $image['size']
        ];

        return $this->save($query, $params, 1);
      }
      return null;
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
