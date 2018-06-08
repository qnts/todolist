<?php

$router = resolve('router');

$router->get('/', 'TodoController@index')->name('home');

// create
$router->get('/todo/create', function () {
    $todo = new App\Models\Todo();
    // old input
    if (session()->hasFlash('old')) {
        $todo->fill(session()->flash('old'));
    }
    return view('edit', compact('todo'));
})->name('todo.create');
$router->post('/todo/create', 'TodoController@store');
// edit
$router->get('/todo/{id}/edit', 'TodoController@edit')->name('todo.edit');
// save edit
$router->put('/todo/{id}', 'TodoController@save')->name('todo.save');
// delete
$router->delete('/todo/{id}', 'TodoController@delete')->name('todo.delete');
