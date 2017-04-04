<?php
namespace app\string;

class Url
{
  protected static $url = [
    'LOGIN'           =>  "/user/login",
    'LOGOUT'          =>  "/user/logout",
    'PUBLIC'          =>  "/",
    'PROFILE'         =>  "/user/profile",

    'PAGES'           =>  "/pages",
    'POSTS'           =>  "/posts",
    'SETTINGS'        =>  "/settings",
    'USERS'           =>  "/user/all",

    'NEW_PAGE'        =>  "/pages/new",
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
