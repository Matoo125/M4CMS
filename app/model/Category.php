<?php

namespace m4\m4cms\model;

use m4\m4mvc\core\Model;
use m4\m4mvc\helper\Str;

class Category extends Model
{

  protected static $table = "categories";

  public function insert($data)
  {
    $query = $this->query->insert('title', 'slug', 'description', 'page_id', 'image_id')
                         ->into(self::$table)
                         ->build();
    $params = [
      'title'         => $data['title'],
      'slug'          => Str::slugify($data['title']),
      'description'   => $data['description'],
      'page_id'       => $data['page_id'],
      'image_id'      => $this->image($data['image'], self::$table)
    ];
    return $this->save($query, $params, 1);

  }

  public function update($id, $data)
  {
    if (empty($data)) return;

    $params['id'] = $id;

    if (isset($data['image'])) {
      $data['image_id'] = $this->image($data['image'], self::$table);
      unset($data['image']);
    }

    foreach($data as $key => $value){
      $params[$key] = $value;
    }

    $query = $this->query->update(self::$table)
                         ->set(array_keys($data))
                         ->where('id = :id')
                         ->build();

    return $this->save($query, $params);

  }

  public function getById($id)
  {
    $query = $this->query->select('c.id', 'c.title', 'c.slug', 'c.page_id', 'c.description', 'CONCAT(i.folder, "/", i.name) AS image', 'c.created_at', 'c.updated_at')
                          ->from(self::$table . " AS c")
                          ->join('left', 'images AS i', 'i.id = c.image_id')
                          ->where("c.id = :id")
                          ->build();
    $params = ['id' => $id];
    return $this->fetch($query, $params);
  }

  public function getAll()
  {
    $query = $this->query->select('c.id', 'c.title', 'c.slug', 'p.title AS page', 'COUNT(DISTINCT po.id) posts' , 'c.description', 'CONCAT(i.folder, "/", i.name) AS image', 'c.created_at', 'c.updated_at')
                          ->from(self::$table . " AS c")
                          ->join('left', 'images AS i', 'i.id = c.image_id')
                          ->join('left', 'posts AS po', 'po.category_id = c.id')
                          ->join('inner', 'pages AS p ', 'p.id = c.page_id')
                          ->groupBy('c.id')
                          ->build();
    return $this->fetchAll($query, []);
  }

  public function getPages()
  {
    $query = $this->query->select('title', 'id')
                          ->from("pages")
                          ->where("is_published = 1")
                          ->build();
    return $this->fetchAll($query, []);
  }

}
