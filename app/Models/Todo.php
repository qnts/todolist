<?php

namespace App\Models;

use App\Core\Model;

class Todo extends Model
{
    protected $table = 'todos';

    public static function all()
    {
        $db = resolve('db');
        $db->prepare('select * from todos order by start_date asc');
        $db->execute();
        $result = $db->fetchAllAssociative();
        $modelList = [];
        // transform to array of models
        foreach ($result as $data) {
            $modelList[] = new Todo($data);
        }

        return $modelList;
    }
}
