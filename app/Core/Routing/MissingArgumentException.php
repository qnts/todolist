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
        parent::__construct(sprintf('Missing argument "%s" or argument "%s" is empty for route <b>%s</b>', $argument, $routeName));
    }
}