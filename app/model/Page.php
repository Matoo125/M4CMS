<?php

namespace app\model;

use app\core\Model;
use app\helper\Query;
use app\helper\Strings;

class Page extends Model
{
    protected static $table = "pages";

    public function insert($data)
    {

      $query = $this->query->insert('title', 'slug', 'description', 'content', 'is_published', 'image_id')
                           ->into(self::$table)
                           ->build();
      $params = [
        'title'         => $data['title'],
        'slug'          => Strings::slugify($data['title']),
        'description'   => $data['description'],
        'content'       => $data['content'],
        'is_published'  => $data['publish'],
        'image_id'      => $this->image($data['image'])
      ];
      return $this->save($query, $params, 1);

    }

    public function get($id)
    {
      $query = $this->query->select('p.title', 'p.slug', 'p.description', 'p.content', 'p.is_published', 'CONCAT(i.folder, "/", i.name) AS image', 'p.created_at', 'p.updated_at')
                            ->from(self::$table . " AS p")
                            ->join('left', 'images AS i', 'i.id = p.image_id')
                            ->where("p.id = :id")
                            ->build();
      //var_dump($query);die;
      $params = ['id' => $id];
      return $this->fetch($query, $params);
    }

    public function update($id, $data)
    {
      if (empty($data)) return;

      $params = [];
      $params['id'] = $id;


      if (isset($data['image'])) {
        $data['image_id'] = $this->image($data['image']);
        unset($data['image']);
      }


      foreach($data as $key => $value){
        $params[$key] = $value;
      }


      $query = $this->query->update(self::$table)
                           ->set(array_keys($data))
                           ->where('id = :id')
                           ->build();

    return $this->save($query, $params);

    echo $query; die;

    }




}
