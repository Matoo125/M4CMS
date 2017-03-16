<?php
namespace app\string;

class Url
{
  protected static $url = [
    'LOGIN'           =>  "/user/login",
    'LOGOUT'          =>  "/user/logout",
  ];

  public static function get($key)
  {
    return self::$url[$key];
  }

  public static function getAll()
  {
    return self::$url;
  }

}
