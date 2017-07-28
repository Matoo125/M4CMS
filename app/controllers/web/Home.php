<?php

namespace m4\m4cms\controllers\web;

use m4\m4cms\controllers\api\Home as IndexApiController;


class Home extends IndexApiController
{
  public function index ($page = null, $category = null, $post = null)
  {
    $pages = new \m4\m4cms\controllers\api\Pages;
    $this->data['navbar'] = $pages->listBasic();

    $settings = new \m4\m4cms\model\Setting;
    $settings = $settings->getAll();
    foreach($settings as $setting) {
      $this->data['settings'][$setting['name']] = $setting['value'];
    }

    if ($page && !$category && !$post) {
      $this->data['page'] = $pages->slug($page);
      $this->getCategories($this->data['page']['id']);
      $this->view = 'Home/Page.twig';
    }

    else if ($page && $category && $post) {
      $posts = new \m4\m4cms\controllers\api\Posts;
      $this->data['post'] = $posts->slug($post);
      $this->data['page']['id'] = $this->data['post']['page_id'];
      $this->view = 'Home/Post.twig';
    }

    else {
      $posts = new \m4\m4cms\model\Post;
      $this->data['posts'] = $posts->getNewest();
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

    $wc = $posts->listByPageWC($pageId);
    if ($wc) {
      array_push($categoriesWithIdAsKey, [
        'id'    =>  'none',
        'title' =>  'Uncategorized',
        'slug'  =>  'uncategorized',
        'page'  =>  $this->data['categories'][0]['page'] ?? null,
        'posts' =>  $wc,
        'description' =>  'Posts without category'
      ]);
    }

    $this->data['categories'] = $categoriesWithIdAsKey;
  }
}
