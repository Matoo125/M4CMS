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
      $data['is_published'] = $data['is_published'] === 'true' ? 1 : 0; // convert boolean to tinyint
      $data['image_id'] = $data['image_id'] === '' ? null : $data['image_id']; 
      $data['author_id'] = $data['author_id'] ?? 1;
      $data['page_id'] = $data['page_id'] ?: null;
      $data['category_id'] = $data['category_id'] ?: null;

      $query = $this->query->insert(...array_keys($data))
                           ->into(self::$table)
                           ->build();

      return $this->save($query, $data, 1);
  }

  public function update ($data)
  {
      isset($data['title']) ? $data['slug'] = Str::slugify($data['title']) : false;
      $data['is_published'] = $data['is_published'] === 'true' ? 1 : 0; // convert boolean to tinyint
      $data['image_id'] = $data['image_id'] === '' ? null : $data['image_id']; 
      $data['page_id'] = $data['page_id'] ?: null;
      $data['category_id'] = $data['category_id'] ?: null;

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

  public function get ($id, $slug = null)
  {
    $query = $this->query->select('post.id', 
                                  'post.title', 
                                  'post.slug',
                                  'post.description', 
                                  'post.content', 
                                  'post.tags',
                                  'post.is_published',
                                  'page.title AS page', 
                                  'page.id AS page_id', 
                                  'cat.title AS category',
                                  'post.category_id',  
                                  'auth.username AS author', 
                                  'post.author_id',
                                  'CONCAT(i.folder, "/", i.filename) AS image', 
                                  'post.created_at', 
                                  'post.updated_at'
                            )
                          ->from(self::$table . " AS post")
                          ->join('left', 'media AS i', 'i.id = post.image_id')
                          ->join('left', 'categories as cat', 'cat.id = post.category_id')
                          ->join('left', 'pages as page', 'page.id = post.page_id')
                          ->join('left', 'users as auth', 'auth.id = post.author_id')
                          ->where($id ? "post.id = :id" : 'post.slug = :slug')
                          ->build();

    $data = $this->fetch($query, $id ? ['id' => $id] : ['slug' => $slug]);
    $data['is_published'] = $data['is_published'] == 1 ? true : false;
    return $data; 
  }

  public function getAll ($where = null, $data = [], $orderby = ['post.id', 'DESC'], $limit = null)
  {
    $query = $this->query->select('post.id', 
                                  'post.title', 
                                  'post.slug',
                                  'page.title as page', 
                                  'cat.title as category', 
                                  'post.description', 
                                  'post.is_published',
                                  'auth.username as author' ,
                                  'CONCAT(img.folder, "/", img.filename) AS image', 
                                  'post.created_at', 
                                  'post.updated_at')
                         ->from(self::$table . " AS post")
                         ->join('left', 'media as img', 'img.id = post.image_id')
                         ->join('left', 'categories as cat', 'cat.id = post.category_id')
                         ->join('left', 'pages as page', 'page.id = post.page_id')
                         ->join('left', 'users as auth', 'auth.id = post.author_id')
                         ->where($where)
                         ->orderby($orderby[0], $orderby[1])
                         ->limit($limit)
                         ->build();
    return $this->fetchAll($query, $data);
  }

  public function getAllByPage ($pageId)
  {
    return $this->getAll('page.id = :id', ['id' => $pageId]);
  }

  public function getAllByPageWithoutCategory ($pageId)
  {
    return $this->getAll('page.id = :id AND post.category_id IS NULL', ['id' => $pageId]);
  }

  public function getAllByCategory ($categoryId)
  {
    return $this->getAll('cat.id = :id', ['id' => $categoryId]);
  }

  public function getNewest ()
  {
    return $this->getAll(null, null, ['post.updated_at', 'DESC'], 10);
  }


}
