<?php

namespace App\Core\Routing;

class UnknownRouteNameException extends RouteException
{
    public function __construct($routeName)
    {
        parent::__construct(sprintf('Unknown route name "%s"', $routeName));
    }
}