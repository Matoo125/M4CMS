<?php

namespace m4\m4cms\model;

use m4\m4mvc\core\Model;
use m4\m4mvc\helper\Str;
use m4\m4mvc\helper\Session;

class Post extends Model
{

  protected static $table = "posts";

  public function insert ($data)
  {
      $data['slug'] = Str::slugify($data['title']); // create slug from title
      $data['is_published'] = $data['is_published'] ? 1 : 0; // convert boolean to tinyint

      $query = $this->query->insert(...array_keys($data))
                           ->into(self::$table)
                           ->build();

      return $this->save($query, $data, 1);
  }

  public function update ($data)
  {
      isset($data['title']) ? $data['slug'] = Str::slugify($data['title']) : false;
      $data['is_published'] = $data['is_published'] ? 1 : 0; // convert boolean to tinyint

      $query = $this->query->update(self::$table)
                           ->set(array_keys($data))
                           ->where('id = :id')
                           ->build();

      return $this->save($query, $data);
  }

  public function get ($id)
  {
    $query = $this->query->select('post.id', 
                                  'post.title', 
                                  'post.description', 
                                  'post.content', 
                                  'post.tags',
                                  'post.category_id', 
                                  'post.is_published',
                                  'page.title AS page', 
                                  'page.id AS page_id', 
                                  'cat.title AS category', 
                                  'auth.username AS author', 
                                  'post.author_id',
                                  'CONCAT(i.folder, "/", i.name) AS image', 
                                  'post.created_at', 
                                  'post.updated_at'
                            )
                          ->from(self::$table . " AS post")
                          ->join('left', 'images AS i', 'i.id = post.image_id')
                          ->join('left', 'categories as cat', 'cat.id = post.category_id')
                          ->join('left', 'pages as page', 'page.id = cat.page_id')
                          ->join('left', 'users as auth', 'auth.id = post.author_id')
                          ->where("post.id = :id")
                          ->build();

    $data = $this->fetch($query, ['id' => $id]);
    $data['is_published'] = $data['is_published'] == 1 ? true : false;
    return $data; 
  }

  public function getAll ($where = null, $data = [])
  {
    $query = $this->query->select('post.id', 
                                  'post.title', 
                                  'page.title as page', 
                                  'cat.title as category', 
                                  'post.description', 
                                  'post.is_published',
                                  'auth.username as author' ,
                                  'CONCAT(img.folder, "/", img.name) AS image', 
                                  'post.created_at', 
                                  'post.updated_at')
                         ->from(self::$table . " AS post")
                         ->join('left', 'images as img', 'img.id = post.image_id')
                         ->join('left', 'categories as cat', 'cat.id = post.category_id')
                         ->join('left', 'pages as page', 'page.id = cat.page_id')
                         ->join('left', 'users as auth', 'auth.id = post.author_id')
                         ->where($where)
                         ->build();
    return $this->fetchAll($query, $data);
  }

  public function getAllByPage ($pageId)
  {
    return $this->getAll('page.id = :id', ['id' => $pageId]);
  }

  public function getAllByCategory ($categoryId)
  {
    return $this->getAll('cat.id = :id', ['id' => $categoryId]);

  }


}
