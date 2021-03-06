<?php

namespace m4\m4cms\model;

use m4\m4mvc\core\model;

class Media extends Model
{
  protected static $table = 'media';

  public function insert ($data) {
    $query = $this->query->insert(...array_keys($data))
         ->into(self::$table)->build();
    return $this->save($query, $data, 1);
  }

  public function update ($data) {
    $query = $this->query->update(self::$table)
               ->set(array_keys($data))
               ->where('id = :id')
               ->build();

    return $this->save($query, $data);
  }

  public function get ($id) {
    $query = $this->query->select('*')
               ->from(self::$table)
               ->where('id = :id')
               ->build();

    return $this->fetch($query, ['id' => $id]);

  }

  public function getAll () {
    $query = $this->query->select('*')
               ->from(self::$table)
               ->build();

    return $this->fetchAll($query, []);
  }

  public function delete ($id) {
    $sql = "DELETE FROM " . self::$table . " WHERE id = :id";
    return $this->save($sql, ['id' => $id]);

  }

  public function renameFolder ($folder, $tmp_id, $id) {
    if (!file_exists(UPLOADS . DS . $folder . DS . $tmp_id)) {
      return true;
    }
    rename(
      UPLOADS . DS . $folder . DS . $tmp_id,
      UPLOADS . DS . $folder . DS . $id
    );
    $sql = $this->query->update(self::$table)
                       ->set(['folder'])
                       ->where('folder = :folder_old')
                       ->build();
    $this->save($sql, [
      'folder'      =>  $folder . '/' . $id,
      'folder_old'  =>  $folder . '/' . $tmp_id
    ]);
  }
}
