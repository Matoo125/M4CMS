<?php

class IndexController extends Controller
{

    public function __construct($view)
    {
        $this->model = $this->model('Index');
        $this->view  = $view; 
    }

    public function index($page = null, $post = null)
    {
       if ( $page != null ) {
           $this->data['page'] = $page;

           if ( $post != null ) {
               $this->data['post'] = $post;
           }
       } 
    }

    public function public_index() 
    {

    }

    public function api_index()
    {

    }

    public function admin_index()
    {

    }

    public function __destruct()
    {
        $this->view($this->view, $this->data);
    }
}
