<?php

namespace m4\m4cms\model;

use m4\m4mvc\core\Model;
use m4\m4mvc\helper\Str;
use m4\m4mvc\helper\Session;

class Post extends Model
{

  protected static $table = "posts";

  public function insert($data)
  {
    $query = $this->query->insert('title', 'slug', 'description', 'content', 'tags', 'author_id', 'category_id', 'is_published', 'image_id')
                         ->into(self::$table)
                         ->build();

    $params = [
      'title'         => $data['title'],
      'slug'          => Str::slugify($data['title']),
      'description'   => $data['description'],
      'content'       => $data['content'],
      'tags'          => $data['tags'],
      'author_id'     => Session::get('user_id'),
      'category_id'   => $data['category_id'],
      'is_published'  => $data['publish'],
      'image_id'      => $data['image']['error'] === 0 ? $this->image($data['image'], self::$table) : NULL
    ];

    return $this->save($query, $params, 1);
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
  }

  public function get($id)
  {
    $query = $this->query->select(
      'post.id', 'post.title', 'post.description', 'post.content', 'post.tags','post.category_id', 'post.is_published',
      'page.title AS page', 'page.id AS page_id', 'cat.title AS category', 'auth.username AS author', 'post.author_id',
      'CONCAT(i.folder, "/", i.name) AS image', 'post.created_at', 'post.updated_at')
                          ->from(self::$table . " AS post")
                          ->join('left', 'images AS i', 'i.id = post.image_id')
                          ->join('left', 'categories as cat', 'cat.id = post.category_id')
                          ->join('left', 'pages as page', 'page.id = cat.page_id')
                          ->join('left', 'users as auth', 'auth.id = post.author_id')
                          ->where("post.id = :id")
                          ->build();
    //var_dump($query);die;
    $params = ['id' => $id];
    return $this->fetch($query, $params);
  }

  public function getAll()
  {
    $query = $this->query->select('post.id', 'post.title', 'page.title as page', 'cat.title as category', 'post.content', 'post.is_published',
                                  'auth.username as author' ,'CONCAT(img.folder, "/", img.name) AS image', 'post.created_at', 'post.updated_at')
                         ->from(self::$table . " AS post")
                         ->join('left', 'images as img', 'img.id = post.image_id')
                         ->join('left', 'categories as cat', 'cat.id = post.category_id')
                         ->join('left', 'pages as page', 'page.id = cat.page_id')
                         ->join('left', 'users as auth', 'auth.id = post.author_id')
                         ->build();
    return $this->fetchAll($query, []);
  }

  // get list of pages for select
  public function getPages()
  {
    $query = $this->query->select('title', 'id')
                          ->from("pages")
                          ->where("is_published = 1")
                          ->build();
    return $this->fetchAll($query, []);
  }

  // get list of categories for select
  public function getCategories($id)
  {
    $query = $this->query->select('title', 'id')
                          ->from("categories")
                          ->where("page_id = :page_id")
                          ->build();
    return $this->fetchAll($query, ['page_id' => $id]);
  }

}
