<?php
namespace app\controllers\admin;

use app\core\Controller;

class Posts extends Controller
{
  public function index(){

    $query = new \app\helper\Query;
    //$query = $query->select()->from("myTable")->where("x = 2 AND p = 4")->limit(10)->build();
    //$query = $query->insert(['col1', 'col2', 'col3'], ['val1', 'val2', 'val2'])->into('posts')->build();

    $query = $query->update('myTable')->set("name = :name", "age = :age", "year = :year")->where("person = John")->build();

    $this->data['query'] = $query;

  }
}
