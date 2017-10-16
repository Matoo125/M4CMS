<?php 
namespace m4\m4cms\Plugin\Todolist;
use m4\m4cms\core\Plugin;
use m4\m4mvc\helper\Request;
use m4\m4mvc\helper\Response;
use m4\m4mvc\helper\Redirect;

class Controller 
{
  public $model = null;

  public function __construct ()
  {
    $this->model = new Model;  
  }
  public function index ()
  {
   //echo '<pre>'; print_r($this->model->list());die;
    Plugin::is_active(true);
    Plugin::$data = [
      'test' => 'this is text',
      'todos' =>  $this->model->list(),      
      'view'  =>  file_get_contents(dirname(__FILE__) .DS. 'View.twig')
    ];
  }

  public function add ()
  {
    if (!$_POST) Redirect::to('/admin/todolist');
    Request::required('title');

    $this->model->add($_POST['title'], $_SESSION['user_id']);
    $this->index();
  }

  public function toggle ()
  {
    Request::forceMethod('post');
    Request::required('id');
    $this->model->toggle($_POST['id']);

    Response::success("Toggle was performed");

  }

}
