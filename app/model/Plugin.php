<?php

namespace m4\m4cms\model;

use m4\m4mvc\core\model;

class Plugin extends Model
{
    protected static $table = 'plugins';

    public function insert ($data) 
    {
        $query = $this->query->insert(...array_keys($data))
                             ->into(self::$table)
                             ->build();
        return $this->save($query, $data, 1);
    }

    public function update ($data) {
      $query = $this->query->update(self::$table)
                           ->set(array_keys($data))
                           ->where('id = :id')
                           ->build();

      return $this->save($query, $data);
    }

    public function toggle ($id, $field)
    {
      $query = "UPDATE " . self::$table . " SET $field = 1 - $field
                WHERE id = :id";

      return $this->save($query, ['id' => $id]);
    }

    public function delete ($id) 
    {
      $query = $this->query->delete(self::$table)->where('id = :id')->build();
      return $this->save($query, ['id' => $id]);
    }

    public function get ($id) 
    {
        $query = $this->query->select('*')
                             ->from(self::$table)
                             ->where('id = :id')
                             ->build();

        return $this->fetch($query, ['id' => $id]);

    }

    public function getAll ()
    {
      $query = $this->query->select('*')
                           ->from(self::$table)
                           ->build();

      return $this->fetchAll($query, []);
    }

}
