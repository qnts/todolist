<?php

$router = resolve('router');

$router->get('/', 'TodoController@index');

// create
$router->get('/todo/create', function () {
    $todo = new App\Models\Todo();
    // old input
    if (session()->hasFlash('old')) {
        $todo->fill(session()->flash('old'));
    }
    return view('edit', compact('todo'));
});
$router->post('/todo/create', 'TodoController@store');
// edit
$router->get('/todo/{:id}/edit', 'TodoController@edit');
// save edit
$router->put('/todo/{:id}', 'TodoController@save');
// delete
$router->delete('/todo/{:id}', 'TodoController@delete');
