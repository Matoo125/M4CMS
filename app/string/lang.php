<?php

namespace app\string;

class Lang
{
  protected static $lang = [
      'test' => 'test',
  ];

  public static function get($key)
  {
    return self::$lang[$key];
  }

  public static function getAll()
  {
    return self::$lang;
  }

}
