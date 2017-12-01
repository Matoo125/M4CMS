<?php 

namespace m4\m4cms\model;
use m4\m4mvc\core\model;

class Matcher extends Model
{
  protected static $table = 'matcher';

  public function insert (array $data)
  {
    /*
      data has to have:
      - table1
      - table2
      - id1
      - id2    
     */
    $q = $this->query->insert(...array_keys($data))
                     ->into(self::$table);
    return $this->save($q->build(), $data, 1);
  }
}