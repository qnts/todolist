<?php

namespace App\Controllers;

use App\Core\Http\Controller;
use App\Core\Http\Response;
use App\Core\View;
use App\Models\Todo;

class TodoController extends Controller
{
    /**
     * Display homepage
     * @throws \Exception
     */
    public function index()
    {
        $todoList = Todo::all();
        $todos = [];
        foreach ($todoList as $todo) {
            // todo->map return an array of properties
            $todos[] = $todo->map(['start_date' => 'start', 'end_date' => 'end', 'name' => 'title']);
        }
        return view('home', compact('todos'));
    }

    /**
     * Delete a todo
     * @param null|int $id
     * @return string
     */
    public function delete($id = null)
    {
        Todo::delete($id);
        return '1';
    }

    /**
     * Display edit form
     * @param null|int $id
     * @return \App\Core\Http\Response|View
     * @throws \Exception
     */
    public function edit($id = null)
    {
        $todo = Todo::find($id);
        if (!$todo) {
            return response_404();
        }

        // old input
        if (session()->hasFlash('old')) {
            $todo->fill(session()->flash('old'));
        }

        return view('edit', compact('todo'));
    }

    /**
     * Update a todo
     * @param null|int $id
     * @return \App\Core\Http\Response
     */
    public function save($id = null)
    {
        // get origin
        $todo = Todo::find($id);
        if (!$todo) {
            return response_404();
        }
        // @TODO write a validation class

        // pure validating
        $validation = $this->validate($id);
        if ($validation !== true) {
            return $validation;
        }

        // fill new data to model
        $todo->fill(request()->input());
        $todo->save();
        // back
        return to_route('todo.edit', ['id' => $todo->id, '+query' => ['saved' => 1]]);
    }

    /**
     * Create a todo
     */
    public function store()
    {
        // @TODO validate
        // pure validating
        $validation = $this->validate();
        if ($validation !== true) {
            return $validation;
        }
        // save
        $todo = new Todo(request()->input());
        $todo->save();
        return to_route('todo.edit', ['id' => $todo->id, '+query' => ['saved' => 1]]);
    }

    /**
     * Validate request
     * @param null|int $id Pass the id to redirect to edit page when errors occur, else go to create page
     * @return true|Response
     */
    protected function validate($id = null)
    {
        $name = trim(request()->input('name'));
        $errors = [];
        if (empty($name)) {
            $errors[] = 'Name not blank';
        }
        $start_date = \DateTime::createFromFormat('Y-m-d', trim(request()->input('start_date')));
        $end_date = \DateTime::createFromFormat('Y-m-d', trim(request()->input('end_date')));
        if (!$start_date) {
            $errors[] = 'Invalid start date format';
        }
        if (!$end_date) {
            $errors[] = 'Invalid end date format';
        }
        if ($start_date && $end_date && $start_date > $end_date) {
            $errors[] = 'Start date value cannot be after end date';
        }
        if (!empty($errors)) {
            session()->setFlash('errors', $errors);
            // save old input
            session()->setFlash('old', request()->input());
            if ($id) {
                return to_route('todo.edit', ['id' => $id]);
            } else {
                return to_route('todo.edit');
            }
        }
        return true;
    }
}
