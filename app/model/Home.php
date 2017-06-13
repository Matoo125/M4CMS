<?php

namespace m4\m4cms\model;

use m4\m4mvc\core\model;

class Home extends Model
{


  public function numberOfUsers()
  {
    $query = $this->query->select("count(*)")->from("users")->build();
    return $this->fetch($query, []);
  }

  public function numberOfPages()
  {
    $query = $this->query->select("count(*)")->from("pages")->build();
    return $this->fetch($query, []);
  }

  public function numberOfPosts()
  {
    $query = $this->query->select("count(*)")->from("posts")->build();
    return $this->fetch($query, []);
  }

  public function numberOfCategories()
  {
    $query = $this->query->select("count(*)")->from("categories")->build();
    return $this->fetch($query, []);

  }

}
