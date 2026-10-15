<?php

namespace m4\m4cms\core;

use m4\m4cms\model\Media;
use m4\m4cms\model\Post;
use m4\m4cms\model\Setting;
use m4\m4mvc\helper\Str;

/*
 * Glue between the stored homepage block document, the database and
 * BlockRenderer. Used by the public homepage and the admin preview.
 */
class Homepage
{
  const SETTING = 'homepage_blocks';

  // validated document from settings, or null when not set / unusable
  public static function load ()
  {
    $json = (new Setting)->getValue(self::SETTING);
    if ($json === null || $json === '') return null;
    try {
      $doc = BlockSchema::parse($json);
    } catch (\InvalidArgumentException $e) {
      error_log('M4CMS homepage blocks ignored: ' . $e->getMessage());
      return null;
    }
    return $doc['blocks'] ? $doc : null;
  }

  public static function save (array $doc)
  {
    return (new Setting)->set(self::SETTING, json_encode($doc));
  }

  public static function reset ()
  {
    return (new Setting)->set(self::SETTING, null);
  }

  public static function render (array $doc, $theme)
  {
    $media = new Media;
    $posts = new Post;

    $renderer = new BlockRenderer(
      [WEB . DS . 'themes' . DS . $theme . DS . 'blocks', APP . DS . 'blocks'],
      function ($id) use ($media) {
        $row = $media->get($id);
        return $row ? $row['folder'] . '/' . $row['filename'] : null;
      },
      function ($count) use ($posts) {
        $rows = $posts->getAll('post.is_published = 1', null, ['post.updated_at', 'DESC'], $count) ?: [];
        return array_map(function ($post) {
          $post['url'] = '/' . Str::slugify($post['page'] ?? '') . '/'
                       . (Str::slugify($post['category'] ?? '') ?: 'uncategorized') . '/'
                       . $post['slug'];
          return $post;
        }, $rows);
      }
    );
    return $renderer->render($doc);
  }
}
