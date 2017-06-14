<?php
namespace m4\m4cms\controllers\admin;


/*
 * Pages controller.
 */


use m4\m4mvc\helper\Request;
use m4\m4mvc\helper\Response;

use m4\m4cms\controllers\api\Pages as PagesApi;


class Pages extends PagesApi
{
    public function create ()
    {
      Request::forceMethod('post');
      Request::required('title', 'description', 'content', 'is_published');
      $data = Request::select('title', 'description', 'content', 'is_published');

      $this->model->insert($data) ? Response::success('Page was created ') : Response::error('Page was not created. ');
    }

    public function update()
    {
      Request::forceMethod('post');
      Request::required('id');
      // select data to update
      $data = Request::select('id', 'description', 'title', 'content', 'is_published');

      $this->model->update($data) ? Response::success('Page was updated ') : Response::error('Page was not updated. ');

    }

    public function trash(){}

}
