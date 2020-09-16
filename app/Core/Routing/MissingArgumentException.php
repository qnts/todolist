<?php

namespace App\Core\Routing;

class MissingArgumentException extends RouteException
{
    /**
     * MissingArgumentException constructor.
     * @param string $argument
     * @param string $routeName
     */
    public function __construct($argument, $routeName)
    {
        parent::__construct(sprintf('Missing argument "%1$s" or argument "%1$s" is empty for route %2$s', $argument, $routeName));
    }
}