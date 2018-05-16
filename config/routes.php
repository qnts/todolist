<?php

$router = resolve('router');

$router->get('/', 'TodoController@index');

$router->get('/todo/{:id}', 'TodoController@view');
