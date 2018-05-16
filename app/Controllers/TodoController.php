<?php

namespace App\Controllers;

use App\Core\Http\Controller;
use App\Core\View;
use App\Models\Todo;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::all();
        return view('home', compact('todos'));
    }

    public function view($id = null)
    {
        var_dump(func_get_args());
        return '';
    }
}
