<?php

namespace m4\m4cms\core;

/*
 * Renders a validated block document to HTML.
 * Each block uses the Twig partial "<type>.twig", looked up in the theme's
 * blocks folder first and then in the built-in app/blocks folder.
 *
 * Data access is injected so rendering needs no database:
 *   $imageUrl(int $id): ?string        path under /public/uploads
 *   $latestPosts(int $count): array    rows with title and url
 */
class BlockRenderer
{
  private $twig;
  private $imageUrl;
  private $latestPosts;

  public function __construct (array $paths, ?callable $imageUrl = null, ?callable $latestPosts = null)
  {
    $this->twig = new \Twig\Environment(
      new \Twig\Loader\FilesystemLoader(array_values(array_filter($paths, 'is_dir'))),
      ['autoescape' => 'html']
    );
    $this->imageUrl = $imageUrl;
    $this->latestPosts = $latestPosts;
  }

  public function render (array $doc)
  {
    return $this->renderList($doc['blocks'] ?? []);
  }

  private function renderList (array $blocks)
  {
    $html = '';
    foreach ($blocks as $block) {
      $html .= $this->renderBlock($block);
    }
    return $html;
  }

  private function renderBlock (array $block)
  {
    $template = $block['type'] . '.twig';
    if (!$this->twig->getLoader()->exists($template)) {
      return ''; // unknown type from a newer version: skip
    }

    $context = ['block' => $block, 'props' => $block['props']];
    $type = BlockSchema::types()[$block['type']] ?? null;

    if ($type && $type['container'] === 'blocks') {
      $context['children_html'] = $this->renderList($block['children']);
    } elseif ($type && $type['container'] === 'columns') {
      $context['columns_html'] = array_map([$this, 'renderList'], $block['children']);
    }

    if ($block['type'] === 'image') {
      $path = ($this->imageUrl && $block['props']['image_id'])
        ? ($this->imageUrl)($block['props']['image_id']) : null;
      $context['src'] = $path ? '/public/uploads/' . $path : null;
    }
    if ($block['type'] === 'latest_posts') {
      $context['posts'] = $this->latestPosts
        ? ($this->latestPosts)($block['props']['count']) : [];
    }

    return $this->twig->render($template, $context);
  }
}
