<?php

namespace app\core;

/*
 * Core Model
 * is extended by other models
 */

abstract class Model
{
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            try {
                $dns = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
                $db = new PDO($dns, Config::DB_USER, Config::DB_PASSWORD);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $db;
            } catch (\PDOException $e) {
                echo $e->getMessage();
                return null;
            }
        }

        return $db;
    }

    public function uploadImage($image, $folder) {
        $name = rand(100, 1000) . "-" . $image['name'];
        $tmp = $image['tmp_name'];
        $location = ROOT . DS . "uploads" . DS . $folder . DS . $name;
        if(move_uploaded_file($tmp, $location)){
            $this->generateThumbnail($location, 150, 150);
            return $name;
        } else {
            return false;
        }

    }

    public function generateThumbnail($path, $width, $height) {
        $info = getimagesize($path);
        $size = array($info[0], $info[1]);

        if ($info['mime'] == 'image/png') {
            $src = imagecreatefrompng($path);
        } elseif ($info['mime'] == 'image/jpeg') {
            $src = imagecreatefromjpeg($path);
        } elseif ($info['mime'] == 'image/gif') {
            $src = imagecreatefromgif($path);
        } else {
            return false;
        }

        $thumb = imagecreatetruecolor($width, $height);

        $src_aspect = $size[0] / $size[1];
        $thumb_aspect = $width / $height;

        if ($src_aspect < $thumb_aspect) {
            // narrower
            $scale = $width / $size[0];
            $new_size = array($width, $width / $src_aspect);
            $src_pos = array(0, ($size[1] * $scale - $height) / $scale / 2);

        } elseif ($src_aspect > $thumb_aspect) {
            // wider
            $scale = $height / $size[1];
            $new_size = array($height * $src_aspect, $height);
            $src_pos = array(($size[0] * $scale - $width) / $scale / 2, 0);
        } else {
            // same shape
            $new_size = array($width, $height);
            $src_pos = array(0, 0);
        }

        $new_size[0] = max($new_size[0], 1);
        $new_size[1] = max($new_size[1], 1);

        imagecopyresampled($thumb, $src, 0, 0, $src_pos[0], $src_pos[1], $new_size[0], $new_size[1], $size[0], $size[1]);

        return imagepng($thumb, $path . '-thumb');
    }

    public function countTable($table, $where = null, $like = null) {

        $db = self::getDB();

        $sql = "SELECT count(*) FROM " . $table;
        if ($where) $sql .= " WHERE " . $where;
        if ($like) $sql .= " LIKE '" . $like . "'";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? $result[0] : null;
    }
}
