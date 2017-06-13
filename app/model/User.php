<?php

namespace m4\m4cms\model;

use m4\m4mvc\core\Model;

class User extends Model
{

    public function getByEmail($email) {
        $db = static::getDB();
        $stmt = $db->prepare("select * from users where email = :email LIMIT 1");
        $stmt->execute(array(':email' => $email));
        if ($results = $stmt->fetch()) {
            return $results;
        }

        return null;
    }

    public function getAll()
    {
      $query = $this->query->select('username', 'id', 'first_name', 'last_name', 'role')
                           ->from('users')
                           ->build();
      return $this->fetchAll($query, []);    }

    public function getAuthors()
    {
        $query = $this->query->select('username', 'id')
                             ->from('users')
                             ->build();
        return $this->fetchAll($query, []);
    }

}
