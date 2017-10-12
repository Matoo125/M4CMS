<?php

namespace m4\m4cms\model;

use m4\m4mvc\core\Model;
use m4\m4mvc\helper\Str;

class Category extends Model
{

  protected static $table = "categories";

  public function insert ($data)
  {
    $data['slug'] = Str::slugify($data['title']);
    $data['image_id'] = $data['image_id'] === '' ? null : $data['image_id']; 

    $query = $this->query->insert(...array_keys($data))
                         ->into(self::$table)
                         ->build();

    return $this->save($query, $data, 1);

  }

  public function update ($data)
  {
    if (isset($data['title'])) {
      $data['slug'] = Str::slugify($data['title']);
    }
    $data['image_id'] = $data['image_id'] === '' ? null : $data['image_id']; 


    $query = $this->query->update(self::$table)
                         ->set(array_keys($data))
                         ->where('id = :id')
                         ->build();

    return $this->save($query, $data);

  }

  public function delete ($id)
  {
    $query = $this->query->delete(self::$table)->where('id = :id')->build();
    return $this->save($query, ['id' => $id]);
  }

  public function get ($id)
  {
    $query = $this->query->select('c.id', 
                                  'c.title', 
                                  'c.slug', 
                                  'c.page_id', 
                                  'c.description', 
                                  'c.image_id',
                                  'CONCAT(i.folder, "/", i.filename) AS image', 
                                  'c.created_at', 
                                  'c.updated_at'
                            )
                          ->from(self::$table . " AS c")
                          ->join('left', 'media AS i', 'i.id = c.image_id')
                          ->where($id ? "c.id = :id" : 'c.slug = :slug')
                          ->build();
    $params = $id ? ['id' => $id] : ['slug' => $slug];
    return $this->fetch($query, $params);
  }

  public function getAll ($where = null, $data = [])
  {
    $query = $this->query->select('c.id', 
                                  'c.title', 
                                  'c.slug', 
                                  'p.title AS page', 
                                  'COUNT(DISTINCT po.id) posts' , 
                                  'c.description', 
                                  'CONCAT(i.folder, "/", i.filename) AS image', 
                                  'c.created_at', 
                                  'c.updated_at'
                            )
                          ->from(self::$table . " AS c")
                          ->join('left', 'media AS i', 'i.id = c.image_id')
                          ->join('left', 'posts AS po', 'po.category_id = c.id')
                          ->join('left', 'pages AS p ', 'p.id = c.page_id')
                          ->where($where)
                          ->groupBy('c.id')
                          ->build();
    return $this->fetchAll($query, $data);
  }

  public function getForPage ($pageId)
  {
    return $this->getAll('c.page_id = :id', ['id' => $pageId]);
  }

  public function getForPageBasic ($id)
  {
    $query = $this->query->select('title as label', 'id as value')
                         ->from(self::$table)
                         ->where('page_id = :id')
                         ->build();
    return $this->fetchAll($query, ['id' => $id]);
  }

}
