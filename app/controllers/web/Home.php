<?php

namespace m4\m4cms\controllers\web;

use m4\m4cms\controllers\api\Home as IndexApiController;
use m4\m4cms\controllers\api\Pages;
use m4\m4cms\model\Setting;
use m4\m4cms\controllers\api\Posts;
use m4\m4cms\controllers\api\Categories;
use m4\m4cms\core\Shortcode;

class Home extends IndexApiController
{
  public function index ($page = null, $category = null, $post = null)
  {
    $pages = new Pages;
    $this->data['navbar'] = $pages->listBasic(['published' => true]);

    $this->loadSettings();

    if ($page && !$category && !$post) {
      $this->data['page'] = $pages->slug($page);
      $this->data['page']['content'] = (new Shortcode)->handle(
        $this->data['page']['content']
      );

      $this->getCategories($this->data['page']['id']);
      $this->view = 'Home/Page.twig';
    }

    if ($page && $category && !$post) {
      // not done yet
    }

    else if ($page && $category && $post) {
      $posts = new Posts;
      $this->data['post'] = $posts->slug($post);
      $this->data['post']['content'] = (new Shortcode)->handle(
        $this->data['post']['content']
      );
      $this->data['page']['id'] = $this->data['post']['page_id'];
      $this->view = 'Home/Post.twig';
    }

    else {
      $posts = new \m4\m4cms\model\Post;
      $this->data['posts'] = $posts->getNewest();
    }
  }

  protected function loadSettings ()
  {
    $settings = new Setting;
    $settings = $settings->getAll();
    foreach($settings as $setting) {
      $this->data['settings'][$setting['name']] = $setting['value'];
    }
  }

  protected function getCategories($pageId)
  {
    $categories = new Categories;
    $posts = new Posts;
    $this->data['categories'] = $categories->list($pageId);

    $categoriesWithIdAsKey = [];
    $this->data['posts'] = [];
    foreach ($this->data['categories'] as $category) {
      $category['posts'] = $posts->listByCategory($category['id']);
      $categoriesWithIdAsKey[$category['id']] = $category;
      array_push($this->data['posts'], $category['posts']);
    }

    // list posts without category
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
