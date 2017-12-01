<?php 
/* Gallery is made of multiple media elements */
namespace m4\m4cms\model;
use m4\m4mvc\core\model;

class Gallery extends Model
{
  protected static $table = 'gallery';

  public function insert (array $data)
  {
    /*
      data has to have:
      -  title  
     */
    $q = $this->query->insert(...array_keys($data))
                     ->into(self::$table);
    return $this->save($q->build(), $data, 1);
  }

  public function get ($id)
  {
    $q = "SELECT media.id, 
                 media.folder, 
                 media.filename 
          FROM gallery
          LEFT JOIN matcher ON 
               table1 = 'gallery' AND
               table2 = 'media' AND 
               id1 = gallery.id
          LEFT JOIN media ON 
               media.id = matcher.id2
          WHERE gallery.id = :id";

    $items = $this->fetchAll($q, ['id' => $id]);

    $q = "SELECT * FROM gallery WHERE id = :id";
    $gallery = $this->fetch($q, ['id' => $id]);

    $gallery['items'] = $items;

    return $gallery;

  }
}