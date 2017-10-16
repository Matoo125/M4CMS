<?php 

namespace m4\m4cms\core;

class Plugin
{

  public static $admin = [];
  public static $public = [];
  public static $hooks = [
    'navbar'
  ];
  private static $navItems = [];
  private static $paths = [];
  private static $active = false;
  public static $data = [];
  public static $view = '';

  public static function is_active ($bool = null) {
    if (is_null($bool)) {
      return self::$active;
    } else {
      self::$active = $bool;
    }
  }

  public static function getNavItems ()
  {
    return self::$navItems;
  }

  public static function addNavItem ($item)
  {
    array_push(self::$navItems, $item);
  }

  public static function addPath (string $url, string $object, string $method)
  {
    self::$paths[$url] = [
      'object'  =>  $object,
      'method'  =>  $method
    ];
  }

  public static function getPaths ()
  {
    return self::$paths;
  }

  public static function matchUrl ($url)
  {
    if (array_key_exists($url, self::$paths)) {
      $object = new self::$paths[$url]['object'];
      if ($method = self::$paths[$url]['method']) {
        $object->$method();
      }
      return false;
    }
    return false;
  }

  public static function runInAdmin ($function)
  {
    array_push(self::$admin, $function);
  }

  public static function runInPublic ($function)
  {
    array_push(self::$public, $function);
  }

  public static function runAdminNow ()
  {
    foreach (self::$admin as $function) {
      call_user_func($function);
    }
  }

  public static function runPublicNow ()
  {
    foreach (self::$public as $function) {
      call_user_func($function);
    }
  }


}
