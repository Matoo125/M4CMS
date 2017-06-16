<?php
namespace m4\m4cms\controllers\admin;

use m4\m4cms\interfaces\Crud;

use m4\m4cms\controllers\api\Posts as Controller;

use m4\m4mvc\helper\Request;
use m4\m4mvc\helper\Response;


class Posts extends Controller implements Crud 
{
    public static $fields = ['title', 'description', 'content', 'tags', 'page_id', 'category_id', 'is_published', 'author_id', 'image_id'];

    public function save ()
    {
      isset($_POST['id']) ? $this->update() : $this->create();
    }

    public function create ()
    {
        Request::forceMethod('post');
        Request::required('title', 'description', 'content', 'is_published');
        $data = Request::select(...self::$fields);

        $id = $this->model->insert($data);
        $id ? 
        Response::success('Post was created ', ['id' => $id]) : 
        Response::error('Post was not created. ');
    }

    public function update ()
    {
        Request::forceMethod('post');
        Request::required('id');
        $data = Request::select(...self::$fields);
        $data['id'] = $_POST['id'];

        $this->model->update($data) ? 
        Response::success('Post was updated ') : 
        Response::error('Post was not updated. ');
    }

    public function delete () {}
}
