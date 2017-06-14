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
      $data['slug'] = Str::slugify($data['title']); // create slug from title
      $data['is_published'] = $data['is_published'] ? 1 : 0; // convert boolean to tinyint

      $query = $this->query->insert(...array_keys($data))
                           ->into(self::$table)
                           ->build();

      return $this->save($query, $data, 1);

    }

    public function get($id)
    {
      $query = $this->query->select('p.id', 
                                    'p.title', 
                                    'p.slug', 
                                    'p.description', 
                                    'p.content', 
                                    'p.is_published', 
                                    'IF(p.image_id IS NOT NULL, CONCAT(i.folder, "/", i.name), false) AS image', 
                                    'p.created_at', 
                                    'p.updated_at')
                            ->from(self::$table . " AS p")
                            ->join('left', 'images AS i', 'i.id = p.image_id')
                            ->where("p.id = :id")
                            ->build();

      $data = $this->fetch($query, ['id' => $id]);
      $data['is_published'] = $data['is_published'] == 1 ? true : false;
      return $data;
    }

    public function getAll()
    {
      $query = $this->query->select('p.id', 
                                    'p.title', 
                                    'p.slug', 
                                    'p.description', 
                                    'p.content', 
                                    'p.is_published', 
                                    'IF(p.image_id IS NOT NULL, CONCAT(i.folder, "/", i.name), false) AS image', 
                                    'p.created_at', 
                                    'p.updated_at')
                           ->from(self::$table . " AS p")
                           ->join('left', 'images as i', 'i.id = p.image_id')
                           ->build();
      return $this->fetchAll($query, []);
    }

    public function update($data)
    {
      if (isset($data['image'])) {
        $data['image_id'] = $this->image($data['image'], self::$table);
        unset($data['image']);
      }


      $query = $this->query->update(self::$table)
                           ->set(array_keys($data))
                           ->where('id = :id')
                           ->build();

      return $this->save($query, $data);
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
