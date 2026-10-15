<?php

namespace m4\m4cms\model;

use m4\m4mvc\helper\user\UserModel;
use m4\m4mvc\helper\Str;

class User extends UserModel
{
    const ROLE_ADMIN = 4; // value set by the installer

    public static $roles = [4 => 'Admin', 1 => 'User'];

    public static $table = 'users';

    // columns prefixed for queries that join media
    public static $columns = 'id, username, slug, first_name, last_name, about_me, email, role, image_id, created_at, updated_at';

    private function selectWithImage ()
    {
      $columns = array_map(function ($c) { return 'u.' . trim($c); }, explode(',', self::$columns));
      $columns[] = 'IF(u.image_id IS NOT NULL, CONCAT(i.folder, "/", i.filename), NULL) AS image';
      return $this->query->select(...$columns)
                         ->from(self::$table . ' AS u')
                         ->join('left', 'media AS i', 'i.id = u.image_id');
    }

    public function getAll ($items = null)
    {
      $query = $this->selectWithImage()->build();
      return $this->fetchAll($query);
    }

    public function getById ($id)
    {
      $query = $this->selectWithImage()->where('u.id = :id')->build();
      return $this->fetch($query, ['id' => $id]);
    }

    public function update ($data)
    {
      $data = $this->cleanImage($data);
      if (isset($data['username'])) {
        $data['slug'] = Str::slugify($data['username']);
      }
      if (array_key_exists('password', $data)) {
        if ($data['password'] === '' || $data['password'] === null) {
          unset($data['password']); // blank password means keep the current one
        } else {
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
      }

      $query = $this->query->update(self::$table)
                           ->set(array_keys($data))
                           ->where('id = :id')
                           ->build();

      return $this->save($query, $data);
    }

    public function insert ($data)
    {
      $data = $this->cleanImage($data);
      $data['slug'] = Str::slugify($data['username']); // create slug from username
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
      $query = $this->query->insert(...array_keys($data))
                           ->into(self::$table)
                           ->build();

      return $this->save($query, $data, 1);
    }

    public function delete ($id)
    {
      $query = $this->query->delete(self::$table)->where('id = :id')->build();
      return $this->save($query, ['id' => $id]);
    }

    public function countAdmins ()
    {
      $query = $this->query->select('COUNT(*) AS total')
                           ->from(self::$table)
                           ->where('role = :role')
                           ->build();
      $row = $this->fetch($query, ['role' => self::ROLE_ADMIN]);
      return (int) $row['total'];
    }

    // true if another user (not $exceptId) already has this email or username
    public function exists ($email, $username, $exceptId = 0)
    {
      $query = $this->query->select('id')
                           ->from(self::$table)
                           ->where('(email = :email OR username = :username) AND id != :id')
                           ->build();
      return (bool) $this->fetch($query, [
        'email' => $email, 'username' => $username, 'id' => $exceptId
      ]);
    }

    // empty image id from the form means "no image"
    private function cleanImage ($data)
    {
      if (array_key_exists('image_id', $data) && $data['image_id'] === '') {
        $data['image_id'] = null;
      }
      return $data;
    }
}
