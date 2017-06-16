<?php

namespace m4\m4cms\model;

use m4\m4mvc\helper\user\UserModel;

class User extends UserModel
{
    public static $table = 'users';

    public function getAllBasic ()
    {
      $query = $this->query->select('id as value', 'username as label')
                           ->from(self::$table)
                           ->build();
      return $this->fetchAll($query);
    }
}