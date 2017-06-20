<?php

namespace m4\m4cms\model;

use m4\m4mvc\helper\user\UserModel;
use m4\m4mvc\helper\Str;

class User extends UserModel
{
    public static $table = 'users';

    public static $columns = 'id, username, slug, first_name, last_name, about_me, email, role, image_id, created_at, updated_at';

    public function getAllBasic ()
    {
      $query = $this->query->select('id as value', 'username as label')
                           ->from(self::$table)
                           ->build();
      return $this->fetchAll($query);
    }

    public function getAll ($items = null)
    {
      $items = $items ?? self::$columns;
      $query = $this->query->select($items)
                           ->from(self::$table)
                           ->build();
      return $this->fetchAll($query);
    }

    public function getById ($id)
    {
      $query = $this->query->select(self::$columns)
                           ->from(self::$table)
                           ->where('id = :id')
                           ->build();

      return $this->fetch($query, ['id' => $id]);
    }

    public function update ($data)
    {
      $query = $this->query->update(self::$table)
                           ->set(array_keys($data))
                           ->where('id = :id')
                           ->build();

      return $this->save($query, $data);
    }

    public function insert ($data)
    {
      $data['slug'] = Str::slugify($data['username']); // create slug from username
      $query = $this->query->insert(...array_keys($data))
                           ->into(self::$table)
                           ->build();

      return $this->save($query, $data, 1);
    }
}