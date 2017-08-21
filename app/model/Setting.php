<?php

namespace m4\m4cms\model;

use m4\m4mvc\core\model;

class Setting extends Model
{
    protected static $table = 'settings';

    public function insert ($data) {
        $query = $this->query->insert(...array_keys($data))
                 ->into(self::$table)->build();
        return $this->save($query, $data, 1);
    }

    public function update ($name, $value) {
        $query = $this->query->update(self::$table)
                             ->set(['value'])
                             ->where("name = :name")
                             ->build();


        return $this->save($query, ['value' => $value, 'name' => $name]);
    }

    public function get ($id) {
        $query = $this->query->select('*')
                             ->from(self::$table)
                             ->where('id = :id')
                             ->build();

        return $this->fetch($query, ['id' => $id]);

    }

    public function getAll () {
        $query = $this->query->select('*')
                             ->from(self::$table)
                             ->build();

        $stmt = self::getDb()->prepare($query);
        $stmt->execute();

        $result = [];

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[$row['name']] = $row;
        }

        return $result; //$this->fetchAll($query, []);
    }

    public function getValues ()
    {
        $query = $this->query->select('name, value')
                             ->from(self::$table)
                             ->build();

        $stmt = self::getDb()->prepare($query);
        $stmt->execute();

        $result = [];

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $result[$row['name']] = $row['value'];
        }

        return $result;
    }

}
