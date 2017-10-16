<?php 

namespace m4\m4cms\Plugin\Todolist;

use m4\m4mvc\core\Model as BaseModel;

class Model extends BaseModel 
{
  public function install () {
    $sql = file_get_contents(dirname(__FILE__) . DS . 'db.sql');
    return $this->save($sql);
  }

  public function toggle ($id)
  {
    $sql = "UPDATE todolist 
            SET `state` = 1 - `state`
            WHERE id = :id";
    return $this->save($sql, ['id' => $id]);
  }

  public function add ($title, $author)
  {
    $sql = "INSERT INTO todolist (`title`, `author`, `state`) 
            VALUES (:title, :author, :state)";
    return $this->save($sql, [
      'title' => $title, 
      'author' => $author,
      'state' =>  0
    ]);
  }

  public function list ()
  {
    $sql = "SELECT * FROM todolist";
    return $this->fetchAll($sql);
  }

  public function id ()
  {

  }
}