<?php

namespace App\Core\Http;

class Controller
{
    /**
     * Invoke the child controller method
     * @param array $args an array of args passed from router
     * @return Response
     */
    public static function invoke($args)
    {
        $className = 'App\\Controllers\\' . $args['controller'];
        $controller = new $className();
        return call_user_func_array([$controller, $args['method']], $args['args']);
    }
}
