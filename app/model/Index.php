<?php

namespace app\model;

use app\core\model;

class Index extends Model
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
