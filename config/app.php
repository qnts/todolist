<?php

return [
    // the app name
    'name' => 'Todo list',
    // the db config
    'database' => [
        'type' => 'mysql',
        'host' => 'localhost',
        'port' => '3306',
        'user' => 'root',
        'password' => '',
        'db' => 'todolist',
    ],
    // use short_url (e.g http://domain.com/some-route will become /some-route)
    'short_url' => true
];
