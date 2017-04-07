<?php

namespace app\model;

use app\core\model;

class Page extends Model
{
    private $db;
    protected static $table = "pages";

    public function __construct()
    {
        $this->db = static::getDB();
    }


    

}
