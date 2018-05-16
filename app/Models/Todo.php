<?php

namespace App\Models;

use App\Core\Model;

class Todo extends Model
{
    protected $table = 'todos';

    protected $fillable = ['name', 'start_date', 'end_date', 'status'];

    public static function all()
    {
        $db = resolve('db');
        $db->prepare('select * from todos order by start_date asc');
        $db->execute();
        $result = $db->fetchAllAssociative();
        $modelList = [];
        // transform to array of models
        foreach ($result as $data) {
            $model = new Todo();
            $model->_fill($data);
            $modelList[] = $model;
        }

        return $modelList;
    }
}
