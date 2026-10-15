<?php
// Run: php tests/blocks.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/core/BlockSchema.php';
require __DIR__ . '/../app/core/BlockRenderer.php';

use m4\m4cms\core\BlockSchema;
use m4\m4cms\core\BlockRenderer;

$failures = 0;
function check ($name, $ok, $detail = '') {
  global $failures;
  echo ($ok ? "ok   " : "FAIL ") . $name . ($ok ? '' : "  $detail") . "\n";
  if (!$ok) $failures++;
}
function throws ($fn) {
  try { $fn(); } catch (\InvalidArgumentException $e) { return true; }
  return false;
}
function doc ($blocks) { return ['version' => 1, 'blocks' => $blocks]; }
function block ($type, $props = [], $children = []) {
  return ['type' => $type, 'props' => $props, 'children' => $children];
}

// --- schema: defaults and coercion
$d = BlockSchema::normalize(doc([block('heading', ['level' => 99, 'align' => 'evil', 'text' => '<b>Hi</b>'])]));
$p = $d['blocks'][0]['props'];
check('level is clamped', $p['level'] === 4);
check('bad enum falls back to default', $p['align'] === 'left');
check('tags are stripped from plain strings', $p['text'] === 'Hi');
check('missing id is generated', preg_match('/^[a-f0-9]{12}$/', $d['blocks'][0]['id']) === 1);

$d = BlockSchema::normalize(doc([block('spacer')]));
check('missing props get defaults', $d['blocks'][0]['props']['height'] === 32);

$d = BlockSchema::normalize(doc([block('heading', ['bogus' => 1])]));
check('unknown props are dropped', !isset($d['blocks'][0]['props']['bogus']));

// --- schema: rejections
check('unknown type rejected', throws(function () { BlockSchema::normalize(doc([block('nope')])); }));
check('wrong version rejected', throws(function () { BlockSchema::normalize(['version' => 2, 'blocks' => []]); }));
check('invalid json rejected', throws(function () { BlockSchema::parse('{not json'); }));
check('oversize rejected', throws(function () { BlockSchema::parse(str_repeat('a', BlockSchema::MAX_BYTES + 1)); }));
check('columns need 2-4 columns', throws(function () { BlockSchema::normalize(doc([block('columns', [], [[]])])); }));
check('five columns rejected', throws(function () { BlockSchema::normalize(doc([block('columns', [], [[],[],[],[],[]])])); }));

$deep = block('spacer');
for ($i = 0; $i < 6; $i++) $deep = block('group', [], [$deep]);
check('too deep rejected', throws(function () use ($deep) { BlockSchema::normalize(doc([$deep])); }));

$many = [];
for ($i = 0; $i < BlockSchema::MAX_BLOCKS + 1; $i++) $many[] = block('spacer');
check('too many blocks rejected', throws(function () use ($many) { BlockSchema::normalize(doc($many)); }));

// --- sanitizer and urls
$h = BlockSchema::sanitizeHtml('<p onclick="x()">a<script>alert(1)</script><img src=x onerror=y> <a href="javascript:alert(1)" style="c">l</a> <a href="https://e.com/">ok</a></p>');
check('script and img removed', strpos($h, '<script') === false && strpos($h, '<img') === false);
check('event handlers removed', strpos($h, 'onclick') === false && strpos($h, 'onerror') === false);
check('javascript: link dropped', strpos($h, 'javascript') === false);
check('safe link kept', strpos($h, 'href="https://e.com/"') !== false);
check('safe url types', BlockSchema::safeUrl('/a') === '/a' && BlockSchema::safeUrl('//evil.com') === null
  && BlockSchema::safeUrl('data:text/html,x') === null && BlockSchema::safeUrl('mailto:a@b.c') === 'mailto:a@b.c');

// --- renderer
$themeDir = sys_get_temp_dir() . '/m4blocks_' . getmypid();
mkdir($themeDir);
$renderer = function () use ($themeDir) {
  return new BlockRenderer(
    [$themeDir, __DIR__ . '/../app/blocks'],
    function ($id) { return $id === 7 ? 'users/7/a.png' : null; },
    function ($n) { return [['title' => 'First <post>', 'url' => '/p/1']]; }
  );
};

$document = BlockSchema::normalize(doc([
  block('heading', ['text' => 'A & B']),
  block('columns', [], [
    [block('text', ['html' => '<p>left</p>'])],
    [block('image', ['image_id' => 7, 'alt' => 'x"y']), block('button', ['url' => 'javascript:evil()', 'label' => 'Go'])],
  ]),
  block('latest_posts'),
]));
$html = $renderer()->render($document);
check('heading is escaped', strpos($html, '<h2') !== false && strpos($html, 'A &amp; B') !== false);
check('columns render both columns', substr_count($html, 'm4-column') === 2);
check('image resolves url', strpos($html, '/public/uploads/users/7/a.png') !== false);
check('alt is escaped', strpos($html, 'alt="x&quot;y"') !== false);
check('unsafe button url replaced', strpos($html, 'javascript') === false && strpos($html, 'href="#"') !== false);
check('post titles are escaped', strpos($html, 'First &lt;post&gt;') !== false);

$html = $renderer()->render(doc([block('image', ['image_id' => 99])]));
check('image without media renders nothing', trim($html) === '');

$unknown = $renderer()->render(doc([['type' => 'future', 'props' => [], 'children' => []]]));
check('unknown type is skipped at render time', $unknown === '');

file_put_contents($themeDir . '/spacer.twig', '<hr class="theme-spacer">');
check('theme partial overrides built-in', strpos($renderer()->render(doc([block('spacer')])), 'theme-spacer') !== false);
unlink($themeDir . '/spacer.twig');
rmdir($themeDir);

echo $failures ? "\n$failures failed\n" : "\nall passed\n";
exit($failures ? 1 : 0);
