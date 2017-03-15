<?php

class Controller {

    protected $model;
    protected $view;
    protected $data;
    
    /**
     * @param $model String
     * @return mixed new instance of model
     */
    public function model($model) {
        require_once APP . DS . 'model' . DS . $model . '.php';
        return new $model;
    }

    public function view($view = "404", $data = []) {
        require_once APP . DS . 'view' . DS . $view . '.php';
    }

}