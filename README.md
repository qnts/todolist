# todolist
A simple MVC todolist using pure php

## Configuration and Installation

Open `config/app.php` to change database settings to your own.

1. Create a mysql database, import `todos.sql`
2. Cd into the app root (the same directory as `README.md`)
3. Run `composer install` to update autoload map
4. Run `php -S localhost:8000 -t html` to start a built-in server
5. Open browser and navigate to [`http://localhost:8000`](http://localhost:8000)

## TODO

- Adds a validation class
- Implements UnitTest
