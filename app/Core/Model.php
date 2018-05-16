<?php

namespace App\Core;

class Model
{
    /**
     * Database object
     */
    protected $db;
    /**
     * Table name associated with this model
     */
    protected $table;
    /**
     * Model properties
     */
    protected $properties = [];

    public function __construct($properties = [])
    {
        $this->db = resolve('db');
        $this->properties = $properties;
    }

    /**
     * Look for a property and return it if found
     * @param $property string
     */
    public function __get($property)
    {
        if (isset($this->properties[$property])) {
            return $this->properties[$property];
        }
        return;
    }

    public function __set($property, $value)
    {
        $this->properties[$property] = $value;
    }

    public function toArray()
    {
        return $this->properties;
    }

    public static function find($id)
    {
        $db = resolve('db');
        $contextModel = new static();
        $db->prepare('SELECT * FROM ' . $contextModel->table . ' WHERE id = :id LIMIT 1');
        $db->bindValue(':id', $id);
        $db->execute();
        $result = $db->fetchAssociative();
        // assoc the object to this model properties
        if ($result) {
            $model = new static($result);
            return $model;
        }
        return;
    }
}
