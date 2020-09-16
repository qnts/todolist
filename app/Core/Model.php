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
    /**
     * Fillable properties
     */
    protected $fillable = [];

    public function __construct($properties = [])
    {
        $this->db = resolve('db');
        $this->fill($properties);
    }

    /**
     * Look for a property and return it if found
     * @param string $property
     * @return mixed|void
     */
    public function __get($property)
    {
        if (isset($this->properties[$property])) {
            return $this->properties[$property];
        }
        return;
    }

    /**
     * Set a property using magic method
     * @param $property
     * @param $value
     */
    public function __set($property, $value)
    {
        $this->properties[$property] = $value;
    }

    /**
     * Return model properties as json string
     * @param array $maps
     * @return string
     */
    public function toJson($maps = [])
    {
        $newProperties = $this->map($maps);
        return json_encode($newProperties);
    }

    /**
     * Map properties to another names
     * @param array $maps
     * @return array
     */
    public function map($maps = [])
    {
        $newProperties = [];
        foreach ($this->properties as $property => $value) {
            if (isset($maps[$property])) {
                $newProperties[$maps[$property]] = $value;
            } else {
                $newProperties[$property] = $value;
            }
        }
        return $newProperties;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->properties;
    }

    /**
     * Save model to db
     */
    public function save()
    {
        $properties = $this->properties;
        if (!empty($properties)) {
            // update
            if ($this->id) {
                $updateSet = [];
                foreach ($properties as $name => $value) {
                    if ($name == 'id') {
                        continue;
                    }
                    $updateSet[] = "$name = :$name";
                }
                $this->db->prepare('UPDATE ' . $this->table . ' SET ' . implode(', ', $updateSet) . ' WHERE id = :id');
                $this->db->bindValues($properties);
                $this->db->execute();
            } else {
                // insert
                $fields = [];
                $values = [];
                foreach ($properties as $name => $value) {
                    $fields[] = $name;
                    $values[] = ":$name";
                }
                $this->db->prepare('INSERT INTO ' . $this->table . ' ('. implode(', ', $fields) . ') values ('. implode(', ', $values) . ')');
                $this->db->bindValues($properties);
                $this->db->execute();
                // store
                $this->id = $this->db->lastInsertedId();
            }
        }
    }

    /**
     * Enrich the model properties
     * @param array $values
     */
    public function fill(array $values)
    {
        if (!empty($this->fillable)) {
            foreach ($this->fillable as $key) {
                if (isset($values[$key])) {
                    $this->properties[$key] = $values[$key];
                }
            }
        }
    }

    /**
     * Fill the model properties. Ignore $fillable
     * @param array $values
     */
    protected function _fill(array $values)
    {
        $this->properties = array_merge($this->properties, $values);
    }

    /**
     * Delete this model record
     */
    public function remove()
    {
        if ($this->id) {
            static::delete($this->id);
        }
    }

    /**
     * Find a record by id
     * @param $id
     * @return Model|void
     */
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
            $model = new static();
            $model->_fill($result);
            return $model;
        }
        return;
    }

    /**
     * Delete a record using model id
     * @param $id
     */
    public static function delete($id)
    {
        $db = resolve('db');
        $contextModel = new static();
        $db->prepare('DELETE FROM ' . $contextModel->table . ' WHERE id = :id');
        $db->bindValue(':id', $id);
        $db->execute();
    }
}
