<?php

namespace m4\m4cms\model;

use m4\m4mvc\core\Model;
use m4\m4mvc\helper\Query;
use m4\m4mvc\helper\Str;

class Page extends Model
{
    protected static $table = "pages";

    public function insert ($data)
    {
      $data['slug'] = Str::slugify($data['title']); // create slug from title
      $data['is_published'] = $data['is_published'] === 'true' ? 1 : 0; // convert boolean to tinyint
      $data['image_id'] = $data['image_id'] === '' ? null : $data['image_id']; 
      $query = $this->query->insert(...array_keys($data))
                           ->into(self::$table)
                           ->build();

      return $this->save($query, $data, 1);

    }

    public function update ($data)
    {
      if (isset($data['title'])) { $data['slug'] = Str::slugify($data['title']); }
      $data['is_published'] = $data['is_published'] === 'true' ? 1 : 0; // convert boolean to tinyint
      $data['image_id'] = $data['image_id'] === '' ? null : $data['image_id']; 

      $query = $this->query->update(self::$table)
                           ->set(array_keys($data))
                           ->where('id = :id')
                           ->build();

      return $this->save($query, $data);
    }

    public function get ($id, $slug = null)
    {
      $query = $this->query->select('p.id', 
                                    'p.title', 
                                    'p.slug', 
                                    'p.description', 
                                    'p.content', 
                                    'p.content_delta',
                                    'p.is_published', 
                                    'p.image_id',
                                    'IF(p.image_id IS NOT NULL, CONCAT(i.folder, "/", i.filename), false) AS image', 
                                    'p.created_at', 
                                    'p.updated_at')
                            ->from(self::$table . " AS p")
                            ->join('left', 'media AS i', 'i.id = p.image_id')
                            ->where($id ? "p.id = :id" : 'p.slug = :slug')
                            ->build();

      $data = $this->fetch($query, $id ? ['id' => $id] : ['slug' => $slug]);
      $data['is_published'] = $data['is_published'] == 1 ? true : false;
      $data['content'] = (new \m4\m4cms\core\Shortcode)->handle($data['content']);
      return $data;
    }

    public function getAll ($filters = null)
    {
      $published = $filters['published'] ?? null;

      $query = $this->query->select('p.id', 
                                    'p.title', 
                                    'p.slug', 
                                    'p.description', 
                                    'p.content', 
                                    'p.is_published', 
                                    'IF(p.image_id IS NOT NULL, CONCAT(i.folder, "/", i.filename), false) AS image', 
                                    'p.created_at', 
                                    'p.updated_at')
                           ->from(self::$table . " AS p")
                           ->join('left', 'media as i', 'i.id = p.image_id');

      if ($published) {
        $query->where('is_published = 1');
      }

      return $this->fetchAll($query->build(), []);
    }

    public function getAllBasic ($filters = null)
    {
      $published = $filters['published'] ?? null;

      $query = $this->query->select('id as value', 'title as label')
                           ->from(self::$table);

      if ($published) {
        $query->where('is_published = 1');
      }
                           
      return $this->fetchAll($query->build());
    }

    public function delete ($id) 
    {
      $query = $this->query->delete(self::$table)->where('id = :id')->build();
      return $this->save($query, ['id' => $id]);
    }

}
