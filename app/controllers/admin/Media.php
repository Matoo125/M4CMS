<?php
namespace m4\m4cms\controllers\admin;

use m4\m4cms\controllers\api\Media as MediaController;
use m4\m4mvc\helper\Response;
use m4\m4mvc\helper\Request;
use m4\m4cms\interfaces\Crud;
use m4\m4mvc\helper\Str;

class Media extends MediaController 
{

  public function save () 
  {
    isset($_POST['id']) ? $this->update() : $this->create(); 
  }

  public function downloadLink ()
  {
    Request::forceMethod('post');
    $link = $_POST['link'];
    $file = fopen($link, 'r');
    $targetPath = $this->createTargetPath(basename($link));
    file_put_contents($targetPath, $file);
    $this->create($targetPath);
  }

  public function upload ()
  {
    Request::forceMethod('post');
    
    $file = $_FILES['file'];

    if ($file['error'] !== 0) {
        Response::error('There was error during upload');
    }

    $targetPath = $this->createTargetPath($file['name']);
    $upload = move_uploaded_file($file['tmp_name'], $targetPath);
    if (!$upload) { Response::error('We could not move the uploaded file. '); }
    $this->create($targetPath);
  }

  public function uploadMultiple ()
  {
    Request::forceMethod('post');
    //print_r($_FILES);die; 
    $count = count($_FILES['files']['name']);
    $files = $_FILES['files'];

    // add gallery
    $galleryModel = new \m4\m4cms\model\Gallery;
    $galleryId = $galleryModel->insert(['title' => $_POST['galleryTitle']]);
    if (!$galleryId) Response::error('Error while creating gallery');

    $matcherModal = new \m4\m4cms\model\Matcher;

    foreach ($files['name'] as $key => $name) {
      $targetPath = $this->createTargetPath($name);
      $upload = move_uploaded_file($files['tmp_name'][$key], $targetPath);
      if (!$upload) { Response::error('We could not move the uploaded file. '); }
      $data = [
        'filename' => basename($targetPath),
        'alt'      => '',
        'folder'   => $_POST['folder'],
        'type'     => mime_content_type($targetPath),
        'size'     => filesize($targetPath)
      ];
      $mediaId = $this->model->insert($data);
      if (!$mediaId) Response::error('Error while creating media');

      $matcherId = $matcherModal->insert([
        'table1' => 'gallery',
        'table2' => 'media',
        'id1'    => $galleryId,
        'id2'    => $mediaId
      ]);
      if (!$matcherId) Response::error('Error while creating matcher');
    } // end foreach

    $gallery = $galleryModel->get($galleryId);
   // print_r($gallery);die;
    Response::success(
      'Gallery has been created and images uploaded', 
      ['gallery' => $gallery]
    );

  }

  public function createTargetPath ($filename)
  {
    $info = pathinfo($filename);
    $path = UPLOADS . DIRECTORY_SEPARATOR;
    if(isset($_POST['folder'])) {
      $path .= $_POST['folder'] . DIRECTORY_SEPARATOR;
    }

    file_exists($path) ?: mkdir($path, 0755, true);

    $path .= Str::slugify($info['filename']) . '.' . $info['extension'];

    if (file_exists($path)) { $path = $this->getNewTargetPath($path); }

    return $path;
  }

  public function chooseFromGallery ()
  {
    $img = $this->model->get($_GET['id']);
    if ($img) {
      $original = UPLOADS . DS . $img['folder'] . DS . $img['filename'];
      $copy = UPLOADS . DS . $_GET['folder'] . DS . $img['filename'];
      if (!file_exists(UPLOADS . DS . $_GET['folder'])) {
        mkdir(UPLOADS . DS . $_GET['folder'], 0755, true);
      }
      copy($original, $copy);
      $id = $this->model->insert([
        'filename'  =>  $img['filename'],
        'alt'       =>  $img['alt'],
        'folder'    =>  $_GET['folder'],
        'type'      =>  $img['type'],
        'size'      =>  $img['size']
      ]);
      Response::success('Image has been selected', [
        'id'  =>  $id,
        'folder'  =>  $_GET['folder'],
        'filename'  =>  $img['filename']
      ]);
    } else {
      Response::error('Image not found');
    }
  }

  public function create ($targetPath) 
  {
    $data = [
        'filename' => basename($targetPath),
        'alt'      => $_POST['alt'] ?? '',
        'folder'   => $_POST['folder'] ?? '',
        'type'     => mime_content_type($targetPath),
        'size'     => filesize($targetPath),
    ];

    $id = $this->model->insert($data);
    $id ? 
    Response::success('Media has been added. ', [
      'id' => $id, 
      'folder' => $_POST['folder'] ?? '', 
      'filename' => basename($targetPath)
    ]) : 
    Response::error('Media has not been added. ');
  }

  public function update ()
  {
    Request::forceMethod('post');
    Request::required('id');
    $data = Request::select('id', 'folder', 'alt');
    $file = isset($_FILES['file']) ? $_FILES['file'] : null;

    if ($file) {
      if ($file['error'] !== 0) {
          Response::error('There was error during upload');
      }

      $folder = isset($_POST['folder']) ? DS . $_POST['folder'] : '';
      $targetDir = UPLOADS . $folder;
      file_exists($targetDir) ?: mkdir($targetDir, 0755, true);

      $upload = move_uploaded_file($file['tmp_name'], $targetDir . DS . $file['name']);

      !$upload ?? Response::error('We could not move the uploaded file. ');

      $data['filename'] = $file['name'];
      $data['type']     = $file['type'];
      $data['size']     = $file['size'];
      $data['folder']   = $_POST['folder'] ?? '';

    }

    $this->model->update($data) ? 
    Response::success('Media has been updated. ') : 
    Response::error('Media has not been updated. ');
  }

  public function delete ()
  {
    Request::forceMethod('post');
    Request::required('id');
    $id = $_POST['id'];
    $item = $this->model->get($id);
    $folder = $item['folder'] ? DS . $item['folder'] : '';
    $path = UPLOADS . $folder . DS . $item['filename']; 
    if (file_exists($path)) {
      unlink($path);
    }
    $this->model->delete($_POST['id']) ?
    Response::success('Media has been deleted. ') : 
    Response::error('Media has not been deleted. ');
  }

  public function getNewTargetPath ($path) {
    $additional = 1;

    while (file_exists($path)) {
      $info = pathinfo($path);
      if ($additional > 1) {
        $info['filename'] = substr($info['filename'], 0, (strlen($info['filename']) - (strlen((string)$additional) + 2)) );
      }

      $path = $info['dirname'] . DS . $info['filename'] . '(' . $additional . ')' . '.' . $info['extension'];
      $additional++;
    }

    return $path;

  }

}
