<?php
namespace m4\m4cms\controllers\admin;

use m4\m4cms\controllers\web\Home as WebHome;
use m4\m4cms\core\BlockSchema;
use m4\m4cms\core\Homepage as HomepageBlocks;
use m4\m4mvc\helper\Request;
use m4\m4mvc\helper\Response;

// Extends the public Home controller so the preview can reuse its theme data.
class Homepage extends WebHome
{
  public function index ($page = null, $category = null, $post = null)
  {
    $document = HomepageBlocks::load() ?: BlockSchema::emptyDocument();
    $this->data['schema'] = self::inlineJson(BlockSchema::types());
    $this->data['document'] = self::inlineJson($document);
  }

  public function save ()
  {
    Request::forceMethod('post');
    Request::required('document');

    try {
      $doc = BlockSchema::parse($_POST['document']);
    } catch (\InvalidArgumentException $e) {
      Response::error($e->getMessage());
    }

    HomepageBlocks::save($doc) ?
    Response::success('Homepage was saved. ', ['document' => $doc]) :
    Response::error('Homepage could not be saved. ');
  }

  // back to the theme's own homepage
  public function reset ()
  {
    Request::forceMethod('post');
    HomepageBlocks::reset() ?
    Response::success('Homepage was reset to the theme default. ') :
    Response::error('Homepage could not be reset. ');
  }

  // render the posted (unsaved) document inside the real theme
  public function preview ()
  {
    Request::forceMethod('post');
    Request::required('document');

    try {
      $doc = BlockSchema::parse($_POST['document']);
    } catch (\InvalidArgumentException $e) {
      http_response_code(400);
      exit(htmlspecialchars($e->getMessage()));
    }

    parent::index(); // navbar, settings, posts for the theme
    $theme = $this->data['settings']['theme'];
    $this->data['homepage'] = $doc['blocks'] ? HomepageBlocks::render($doc, $theme) : null;
    $this->pathToTheme = WEB . DS . 'themes' . DS . $theme;
    $this->view = 'Home/Index.twig';
    $this->renderTwig();
    exit;
  }

  // JSON that is safe inside <script type="application/json"> and attributes
  private static function inlineJson ($value)
  {
    return json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
  }
}
