<?php

namespace m4\m4cms\core;

/*
 * Single source of truth for homepage blocks: which block types exist,
 * which props they accept and how a stored document is validated.
 *
 * Document: {"version":1,"blocks":[{"id","type","props":{},"children":[]}]}
 * Container blocks: 'blocks' => children is a list of blocks,
 *                   'columns' => children is a list of 2-4 lists of blocks.
 */
class BlockSchema
{
  const VERSION = 1;
  const MAX_DEPTH = 5;
  const MAX_BLOCKS = 200;
  const MAX_BYTES = 262144;

  const ALLOWED_HTML = '<p><br><strong><b><em><i><u><s><ul><ol><li><a><blockquote><h2><h3><h4><code><pre>';

  public static function types ()
  {
    $align = ['type' => 'enum', 'values' => ['left', 'center', 'right'], 'default' => 'left'];

    return [
      'heading' => [
        'label' => 'Heading', 'container' => null,
        'props' => [
          'text'  => ['type' => 'string', 'max' => 200, 'default' => 'Heading'],
          'level' => ['type' => 'int', 'min' => 1, 'max' => 4, 'default' => 2],
          'align' => $align,
        ],
      ],
      'text' => [
        'label' => 'Text', 'container' => null,
        'props' => [
          'html'  => ['type' => 'html', 'max' => 20000, 'default' => '<p>Write something...</p>'],
          'align' => $align,
        ],
      ],
      'image' => [
        'label' => 'Image', 'container' => null,
        'props' => [
          'image_id' => ['type' => 'media', 'default' => null],
          'alt'      => ['type' => 'string', 'max' => 255, 'default' => ''],
          'size'     => ['type' => 'enum', 'values' => ['small', 'medium', 'full'], 'default' => 'full'],
          'align'    => $align,
        ],
      ],
      'button' => [
        'label' => 'Button', 'container' => null,
        'props' => [
          'label' => ['type' => 'string', 'max' => 100, 'default' => 'Click me'],
          'url'   => ['type' => 'url', 'default' => '#'],
          'style' => ['type' => 'enum', 'values' => ['primary', 'secondary'], 'default' => 'primary'],
          'align' => $align,
        ],
      ],
      'spacer' => [
        'label' => 'Spacer', 'container' => null,
        'props' => [
          'height' => ['type' => 'int', 'min' => 8, 'max' => 200, 'default' => 32],
        ],
      ],
      'columns' => [
        'label' => 'Columns', 'container' => 'columns',
        'props' => [
          'gap' => ['type' => 'enum', 'values' => ['none', 'small', 'medium', 'large'], 'default' => 'medium'],
        ],
      ],
      'group' => [
        'label' => 'Group', 'container' => 'blocks',
        'props' => [
          'background' => ['type' => 'color', 'default' => ''],
          'padding'    => ['type' => 'enum', 'values' => ['none', 'small', 'medium', 'large'], 'default' => 'medium'],
        ],
      ],
      'latest_posts' => [
        'label' => 'Latest posts', 'container' => null,
        'props' => [
          'title' => ['type' => 'string', 'max' => 100, 'default' => 'Latest posts'],
          'count' => ['type' => 'int', 'min' => 1, 'max' => 12, 'default' => 5],
        ],
      ],
    ];
  }

  public static function emptyDocument ()
  {
    return ['version' => self::VERSION, 'blocks' => []];
  }

  /*
   * Decode and validate a JSON string.
   * @throws \InvalidArgumentException with a message safe to show the admin
   * @return array normalized document
   */
  public static function parse ($json)
  {
    if (!is_string($json) || strlen($json) > self::MAX_BYTES) {
      throw new \InvalidArgumentException('Homepage is too large.');
    }
    $doc = json_decode($json, true);
    if (!is_array($doc)) {
      throw new \InvalidArgumentException('Homepage data is not valid JSON.');
    }
    return self::normalize($doc);
  }

  public static function normalize (array $doc)
  {
    if (($doc['version'] ?? null) !== self::VERSION) {
      throw new \InvalidArgumentException('Unsupported homepage version.');
    }
    if (!isset($doc['blocks']) || !is_array($doc['blocks'])) {
      throw new \InvalidArgumentException('Homepage has no blocks list.');
    }
    $count = 0;
    $types = self::types();
    return [
      'version' => self::VERSION,
      'blocks'  => self::normalizeList($doc['blocks'], 1, $count, $types),
    ];
  }

  private static function normalizeList ($list, $depth, &$count, $types)
  {
    if (!is_array($list) || (count($list) && array_keys($list) !== range(0, count($list) - 1))) {
      throw new \InvalidArgumentException('Block list is malformed.');
    }
    if ($depth > self::MAX_DEPTH) {
      throw new \InvalidArgumentException('Blocks are nested too deeply.');
    }
    $out = [];
    foreach ($list as $block) {
      $out[] = self::normalizeBlock($block, $depth, $count, $types);
    }
    return $out;
  }

  private static function normalizeBlock ($block, $depth, &$count, $types)
  {
    if (!is_array($block) || !isset($block['type']) || !is_string($block['type'])
        || !isset($types[$block['type']])) {
      throw new \InvalidArgumentException('Unknown block type.');
    }
    if (++$count > self::MAX_BLOCKS) {
      throw new \InvalidArgumentException('Too many blocks.');
    }

    $type = $types[$block['type']];
    $rawProps = isset($block['props']) && is_array($block['props']) ? $block['props'] : [];
    $props = [];
    foreach ($type['props'] as $name => $def) {
      $props[$name] = array_key_exists($name, $rawProps)
        ? self::cleanValue($def, $rawProps[$name])
        : $def['default'];
    }

    $id = $block['id'] ?? null;
    if (!is_string($id) || !preg_match('/^[A-Za-z0-9_-]{1,32}$/', $id)) {
      $id = bin2hex(random_bytes(6));
    }

    $children = [];
    $raw = $block['children'] ?? [];
    if ($type['container'] === 'blocks') {
      $children = self::normalizeList($raw, $depth + 1, $count, $types);
    } elseif ($type['container'] === 'columns') {
      if (!is_array($raw) || count($raw) < 2 || count($raw) > 4) {
        throw new \InvalidArgumentException('Columns need between 2 and 4 columns.');
      }
      foreach ($raw as $column) {
        $children[] = self::normalizeList($column, $depth + 1, $count, $types);
      }
    }

    return ['id' => $id, 'type' => $block['type'], 'props' => $props, 'children' => $children];
  }

  // coerce one prop to its declared type; invalid input falls back to the default
  private static function cleanValue ($def, $value)
  {
    switch ($def['type']) {
      case 'string':
        if (!is_string($value)) return $def['default'];
        return mb_substr(trim(strip_tags($value)), 0, $def['max']);
      case 'html':
        return is_string($value) ? self::sanitizeHtml(mb_substr($value, 0, $def['max'])) : $def['default'];
      case 'int':
        if (!is_numeric($value)) return $def['default'];
        return max($def['min'], min($def['max'], (int) $value));
      case 'enum':
        return in_array($value, $def['values'], true) ? $value : $def['default'];
      case 'media':
        return (is_numeric($value) && (int) $value > 0) ? (int) $value : null;
      case 'url':
        return self::safeUrl($value) ?? $def['default'];
      case 'color':
        return (is_string($value) && preg_match('/^#[0-9a-fA-F]{6}$/', $value)) ? $value : '';
    }
    return $def['default'];
  }

  // allow http(s), mailto, site-relative and #anchor links only
  public static function safeUrl ($url)
  {
    if (!is_string($url)) return null;
    $url = trim($url);
    if ($url === '' || strlen($url) > 2000 || preg_match('/[\x00-\x20]/', $url)) return null;
    if ($url[0] === '#' || ($url[0] === '/' && ($url[1] ?? '') !== '/')) return $url;
    return preg_match('#^(https?://|mailto:)#i', $url) ? $url : null;
  }

  // keep a small set of tags; only <a href> may keep an attribute
  public static function sanitizeHtml ($html)
  {
    $html = strip_tags($html, self::ALLOWED_HTML);
    if (trim($html) === '') return '';

    $dom = new \DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8"?><div id="root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    $root = $dom->getElementById('root') ?? $dom->documentElement;

    foreach ((new \DOMXPath($dom))->query('//*') as $el) {
      $keepHref = null;
      if ($el->nodeName === 'a') {
        $keepHref = self::safeUrl($el->getAttribute('href'));
      }
      while ($el->attributes->length) {
        $el->removeAttributeNode($el->attributes->item(0));
      }
      if ($keepHref !== null) {
        $el->setAttribute('href', $keepHref);
        $el->setAttribute('rel', 'noopener');
      }
    }

    $out = '';
    foreach ($root->childNodes as $child) {
      $out .= $dom->saveHTML($child);
    }
    return $out;
  }
}
