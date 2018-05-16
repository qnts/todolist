<?php

namespace App\Core\Http;

class Controller
{
    public static function invoke($args)
    {
        $className = 'App\\Controllers\\' . $args['controller'];
        $controller = new $className();
        return call_user_func_array([$controller, $args['method']], $args['args']);
    }
}
