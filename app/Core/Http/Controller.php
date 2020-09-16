<?php

namespace App\Core\Http;

use App\Core\Routing\Route;
use App\Core\Utilities\FunctionInjector;

class Controller
{
    /**
     * Invoke the child controller method
     * @param Route $route
     * @param FunctionInjector $injector
     * @return Response
     * @throws \ReflectionException
     */
    public static function invoke(Route $route, FunctionInjector $injector)
    {
        $handler = $route->getHandler();
        $controller = new $handler['controller']();
        return $injector->run([$controller, $handler['method']], $route->getArgs());
    }
}
