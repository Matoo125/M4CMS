<?php

namespace app\helper;


/*
 *  Query helper class
 *  builds MySql queries
 *  written by Matej Vrzala
 *  created at: 7.5.2017
 */

class Query
{
    private $action;
    private $columns = array();
    private $values = array();
    private $set = array();
    private $table;
    private $where;
    private $limit;

    public function select()
    {
        $this->columns = func_get_args();
        $this->action = 1;
        return $this;
    }

    public function insert($columns, $values)
    {
      $this->columns = $columns;
      $this->values = $values;
      $this->action = 2;
      return $this;
    }

    public function update($table)
    {
      $this->table = $table;
      $this->action = 3;
      return $this;
    }

    public function delete($table)
    {
      $this->table = $table;
      $this->action = 4;
      return $this;
    }


    public function from($table)
    {
      $this->table = $table;
      return $this;
    }

    public function into($table)
    {
      $this->table = $table;
      return $this;
    }


    public function where($where)
    {
      $this->where = $where;
      return $this;
    }

    public function limit($limit)
    {
      $this->limit = $limit;
      return $this;
    }



    public function set()
    {
      $this->set = func_get_args();
      return $this;
    }


    public function build()
    {

      if (!$this->table){
        throw new \Exception("Query could not be build. Missing table");
      }

      switch($this->action) {
        // select
        case 1:
          $query = "SELECT ";
          if (empty($this->columns)) {
            $query .= "* ";
          } else {
            $query .= implode(', ', $this->columns) . " ";
          }

          $query .= "FROM " . $this->table . " ";

          if (!empty($this->where)) {
            $query .= "WHERE ";
            $query .= $this->where;
            $query .= " ";
          }

          if (!empty($this->limit)) {
            $query .= "LIMIT ";
            $query .= $this->limit;
          }

          return $query;

          break;
        // insert
        case 2:
          $query = "INSERT INTO " . $this->table . " ";
          $query .= "(" . implode(', ', $this->columns) . ") ";
          $query .= "VALUES (:" . implode(', :', $this->values) . ") ";
          return $query;

        // update
        case 3:
          $query = "UPDATE " . $this->table . " ";
          $query .= "SET " . implode(', ', $this->set) . " ";
          if (!empty($this->where)) {
            $query .= "WHERE ".  $this->where;
          }
          return $query;

        // delete
        case 4:

        // error
        default:
          throw new \Exception("Query could not be build. No action selected. ");
          break;
      }
    }

}
