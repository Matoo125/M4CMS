<?php

namespace app\helper;

use Intervention\Image\ImageManager;

/*
 * Helper class for uploading
 * images, generating thumbnails
 * and also
 */

class Image
{
  public static $name;
  public static $extension;
  public static $path;

  public static function upload($image, $folder)
  {
    // set upload location
    $location = ROOT . DS ."public" . DS . "uploads" . DS . "images" . DS . $folder;

    // create directory if not exists
    if (!file_exists($location)) {
      mkdir($location, 0777, true);
    }

    // cut extension from image name
    $explore = explode('.', $image['name']);
    self::$extension = end($explore);
    // cut filename
    self::$name = substr($image['name'], 0, - strlen(self::$extension));
    // create slug
    self::$name = Strings::slugify(self::$name);

    // join them again together
    $name = self::$name . "." . self::$extension;

    // append filename to the location
    $location .= DS . $name;
    if(!move_uploaded_file($image['tmp_name'], $location)){
      return false;
    }
    self::generateThumbnail($location, 150, 150);

    $image['name'] = $name;
    return $image;
  }

  public static function generateThumbnail($path, $width, $height)
  {
    // replace images folder with thumbs/size
    $path2 = preg_replace('/images/', 'thumbs' . DS . DS .  $width."x".$height,  $path, 1);

    // create folder if it doesn't exists
    if(!is_dir(dirname($path2))) {
      mkdir(dirname($path2), 0777, true);
    }


    if (!extension_loaded('imagick')) {
        $manager = new ImageManager();
    }
    else {
        $manager = new ImageManager(array('driver' => 'imagick'));
    }

    // to resize image and add whitespace
    // to fill new aspect ratio
    $image = $manager->make($path)->resize($width, $height, function ($c) {
      $c->aspectRatio();
      $c->upsize();
    })->resizeCanvas($width, $height, 'center', false, array(255, 255, 255, 1))->save($path2);


     // To resize image
     // and make it fit
    $image = $manager->make($path)->fit($width, $height, function($constrain) {
      $constrain->upsize();
    })->save($path2);


  }

}
