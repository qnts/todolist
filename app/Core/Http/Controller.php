<?php

namespace App\Core\Http;

use App\Core\Routing\Route;

class Controller
{
    /**
     * Invoke the child controller method
     * @param Route $route
     * @return Response
     */
    public static function invoke(Route $route)
    {
        $handler = $route->getHandler();
        $controller = new $handler['controller']();
        return call_user_func_array([$controller, $handler['method']], $route->getArgs());
    }
}
