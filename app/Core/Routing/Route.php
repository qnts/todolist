<?php

namespace App\Core\Routing;


class Route
{
    /**
     * @var string Route path
     */
    protected $path;

    /**
     * @var mixed
     */
    protected $handler;

    /**
     * @var array Array of route's named args
     */
    protected $args = [];

    /**
     * @var string Parsed regex string
     */
    protected $regex;

    protected $patterns = [
        'any'  => '.*',
        'id'   => '[0-9]+',
        'slug' => '[a-zA-Z0-9\-]+',
        'name' => '[a-zA-Z]+',
    ];

    /**
     * @const string Named pattern regex
     */
    const REGEX = '#({.+?})#';

    /**
     * Route constructor.
     * @param string $path
     * @param mixed $handler
     */
    public function __construct($path, $handler)
    {
        $this->path = $path;
        $this->parseHandler($handler);
        $this->parseRouteRegex();
    }

    protected function parseHandler($handler)
    {
        if (is_string($handler)) {
            list($controller, $method) = explode('@', $handler);
            if (strpos($controller, '|') === false) {
                $controller = '\\App\\Controllers\\' . $controller;
            } else {
                $controller = str_replace('|', '\\', $controller);
            }

            $this->handler = [
                'controller' => $controller,
                'method' => $method ?: 'index'
            ];
        } else {
            $this->handler = $handler;
        }
    }

    /**
     * Parse route regex
     * @return void
     */
    protected function parseRouteRegex()
    {
        // An example route: /my-awesome-post/{post:[0-9]+}/
        // $fullRouteRegex result: /my-awesome-post/[0-9]+/
        $fullRouteRegex = preg_replace_callback(self::REGEX, function ($matches) {
            $patterns = $this->patterns;
            // first strips the brackets {}
            $match = str_replace(['{', '}'], '', $matches[0]);
            // match now might be "abc:regex"
            // now looks for the regex pattern by split $match into arrays
            // note that a regex may contain ":" so we only split the first ":"
            // as a result, $args contains up to 2 items
            // the first is the name, the second is the regex pattern or name of a predefined pattern
            $args = explode(':', $match, 2);
            // if $args only contains 1 item, we also treat the name as a predefined pattern name
            $pattern = isset($args[1]) ? $args[1] : $args[0];

            // if pattern has been predefined (see $this->patterns)
            if (in_array($pattern, array_keys($patterns))) {
                // replace the {pattern_name:pattern} by the predefined pattern
                // e.g /{id}/ will become /[0-9]+/
                return $patterns[$pattern];
            } else {
                // if no predefined patterns are found, use :any
                return $patterns['any'];
            }
        }, $this->path);

        $this->regex = $fullRouteRegex;
    }

    /**
     * Check if this route matches the request Uri and if it does, parse the route args
     * @param $requestUri
     * @return bool
     */
    public function match($requestUri)
    {
        if (preg_match('#^' . $this->regex . '$#', $requestUri)) {
            // this.route = /{id:[0-9]+}/edit
            // this.regex = /[0-9]+/edit
            // requestUri = /1/edit
            $routePath = explode('/', $this->path);
            $uriPath = explode('/', $requestUri);
            for ($i = 0; $i < count($routePath); $i++) {
                $routeSegment = $routePath[$i];
                $uriSegment = $uriPath[$i];
                // if routeSegment is {id:...}
                if (preg_match(self::REGEX, $routeSegment)) {
                    $argName = explode(':', $routeSegment, 2)[0];
                    // add id => uriSegment into args list
                    $this->args[$argName] = $uriSegment;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Generate url for this route, using $args.
     * @param array $args
     * @return string
     * @throws MissingArgumentException
     */
    public function toUrl($args = [])
    {
        $args = array_merge($this->args, $args);
        $routePath = explode('/', $this->path);
        $url = [];
        for ($i = 0; $i < count($routePath); $i++) {
            $routeSegment = $routePath[$i];
            // if routeSegment is {id:...}
            if (preg_match(self::REGEX, $routeSegment)) {
                $argName = explode(':', str_replace(['{', '}'], '', $routeSegment))[0];
                if (empty($args[$argName])) {
                    throw new MissingArgumentException($argName, $this->path);
                } else {
                    $url[] = $args[$argName];
                }
            } else {
                $url[] = $routeSegment;
            }
        }

        $urlString = trim(implode('/', $url), '/');

        // build query string
        if (!empty($args['+query'])) {
            $urlString .= '?' . http_build_query($args['+query']);
        }

        return Router::getSchema() . $urlString;
    }

    /**
     * Get registered route
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param array $args
     */
    public function setArgs($args)
    {
        $this->args = $args;
    }

    /**
     * Get route args, only if this route->match return true. If no, return an empty array
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Get the parsed route regex
     * @return string
     */
    public function getRegex()
    {
        return $this->regex;
    }

}