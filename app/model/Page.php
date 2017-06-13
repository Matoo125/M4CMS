<?php

namespace m4\m4cms\model;

use m4\m4mvc\core\Model;
use m4\m4mvc\helper\Query;
use m4\m4mvc\helper\Str;

class Page extends Model
{
    protected static $table = "pages";

    public function insert($data)
    {

      $query = $this->query->insert('title', 'slug', 'description', 'content', 'is_published', 'image_id')
                           ->into(self::$table)
                           ->build();

      $params = [
        'title'         => $data['title'],
        'slug'          => Str::slugify($data['title']),
        'description'   => $data['description'],
        'content'       => $data['content'],
        'is_published'  => $data['publish'],
        'image_id'      => $data['image']['error'] === 0 ? $this->image($data['image'], self::$table) : NULL
      ];
      return $this->save($query, $params, 1);

    }

    public function get($id)
    {
      $query = $this->query->select('p.id', 'p.title', 'p.slug', 'p.description', 'p.content', 'p.is_published', 'CONCAT(i.folder, "/", i.name) AS image', 'p.created_at', 'p.updated_at')
                            ->from(self::$table . " AS p")
                            ->join('left', 'images AS i', 'i.id = p.image_id')
                            ->where("p.id = :id")
                            ->build();
      //var_dump($query);die;
      $params = ['id' => $id];
      return $this->fetch($query, $params);
    }

    public function getAll()
    {
      $query = $this->query->select('p.id', 'p.title', 'p.slug', 'p.description', 'p.content', 'p.is_published', 'CONCAT(i.folder, "/", i.name) AS image', 'p.created_at', 'p.updated_at')
                           ->from(self::$table . " AS p")
                           ->join('inner', 'images as i', 'i.id = p.image_id')
                           ->build();
      return $this->fetchAll($query, []);
    }

    public function update($id, $data)
    {
      if (empty($data)) return;

      $params = [];
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

    echo $query; die;

    }

    public function getCategories($id)
    {
      $query = $this->query->select('title', 'id')
                            ->from("categories")
                            ->where("page_id = :id")
                            ->build();
      $params = ['id' => $id];
      return $this->fetchAll($query, $params);
    }




}
