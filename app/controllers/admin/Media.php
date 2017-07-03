<?php
namespace m4\m4cms\controllers\admin;

use m4\m4cms\controllers\api\Media as MediaController;
use m4\m4mvc\helper\Response;
use m4\m4mvc\helper\Request;
use m4\m4cms\interfaces\Crud;

class Media extends MediaController implements Crud 
{

  public function save () 
  {
    isset($_POST['id']) ? $this->update() : $this->create(); 
  }

  public function create () 
  {
    Request::forceMethod('post');
    // var_dump($_FILES); print_r($_POST);die;

    $file = $_FILES['file'];

    if ($file['error'] !== 0) {
        Response::error('There was error during upload');
    }

    $folder = $_POST['folder'] ? DS . $_POST['folder'] : '';
    $targetDir = UPLOADS . $folder;
    file_exists($targetDir) ?: mkdir($targetDir, 0755, true);

    $upload = move_uploaded_file($file['tmp_name'], $targetDir . DS . $file['name']);

    !$upload ?? Response::error('We could not move the uploaded file. ');

    $data = [
        'filename' => $file['name'],
        'alt'      => $_POST['alt'] ?? '',
        'folder'   => $_POST['folder'] ?? '',
        'type'     => $file['type'],
        'size'     => $file['size'],

    ];

    $id = $this->model->insert($data);
    $id ? 
    Response::success('Media has been added. ', ['id' => $id]) : 
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

}
