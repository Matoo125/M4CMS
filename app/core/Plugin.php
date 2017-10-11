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

  public static function getNavItems ()
  {
    return self::$navItems;
  }

  public static function addNavItem ($item)
  {
    array_push(self::$navItems, $item);
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
