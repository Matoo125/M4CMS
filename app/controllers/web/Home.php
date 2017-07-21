<?php

namespace m4\m4cms\controllers\web;

use m4\m4cms\controllers\api\Home as IndexApiController;


class Home extends IndexApiController
{
  public function index ($page = null, $category = null, $post = null)
  {
    $pages = new \m4\m4cms\controllers\api\Pages;
    $this->data['navbar'] = $pages->listBasic();

    if ($page && !$category && !$post) {
      $this->data['page'] = $pages->id($page);
      $this->getCategories($page);
      $this->view = 'Home/Page.twig';
    }

    if ($page && $category && $post) {
      $posts = new \m4\m4cms\controllers\api\Posts;
      $this->data['post'] = $posts->id($post);
      $this->view = 'Home/Post.twig';
    }
  }

  protected function getCategories($pageId)
  {
    $categories = new \m4\m4cms\controllers\api\Categories;
    $posts = new \m4\m4cms\controllers\api\Posts;
    $this->data['categories'] = $categories->list($pageId);

    $categoriesWithIdAsKey = [];
    foreach ($this->data['categories'] as $category) {
      $category['posts'] = $posts->listByCategory($category['id']);
      $categoriesWithIdAsKey[$category['id']] = $category;
    }
    $this->data['categories'] = $categoriesWithIdAsKey;
  }
}
