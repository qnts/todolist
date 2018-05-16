<?php

namespace App\Core;

class Router
{
    /**
     * @var array list of registered routes
     */
    public $routes = [
        'GET'    => [],
        'POST'   => [],
        'ANY'    => [],
        'PUT'    => [],
        'DELETE' => [],
    ];

    /**
     * @var array Predefined patterns for routing
     */
    public $patterns = [
        ':any'  => '.*',
        ':id'   => '[0-9]+',
        ':slug' => '[a-z\-]+',
        ':name' => '[a-zA-Z]+',
    ];

    /**
     * Named pattern regex
     */
    const REGVAL = '#({:.+?})#';

    /**
     * Match an url with any methods
     * @param string $path
     * @param callable $handler
     */
    public function any($path, $handler)
    {
        $this->addRoute('ANY', $path, $handler);
    }

    /**
     * Register a get route
     * @param string $path
     * @param callable $handler
     */
    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Register a post route
     * @param string $path
     * @param callable $handler
     */
    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * Register a put route
     * @param string $path
     * @param callable $handler
     */
    public function put($path, $handler)
    {
        $this->addRoute('PUT', $path, $handler);
    }

    /**
     * Register a delete route
     * @param string $path
     * @param callable $handler
     */
    public function delete($path, $handler)
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    /**
     * Parse the request and determine which route will be used
     * @return array|void
     */
    public function parse()
    {
        $app = app();
        $method = $app->request()->method;
        $requestUri = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';

        foreach ($this->routes[$method] as $resource) {
            $args    = [];
            $route   = key($resource);
            $handler = reset($resource);
            if (preg_match(self::REGVAL, $route)) {
                list($args, $uri, $route) = $this->parseRegexRoute($requestUri, $route);
            }

            if (!preg_match("#^$route$#", $requestUri)) {
                unset($this->routes[$method]);
                continue ;
            }

            if (is_string($handler) && strpos($handler, '@')) {
                list($ctrl, $method) = explode('@', $handler);
                return ['controller' => $ctrl, 'method' => $method, 'args' => $args];
            }

            return ['handler' => $handler, 'args' => $args];
        }

        return;
    }

    /**
     * Add a route to routes list
     * @param string $method
     * @param string $path
     * @param callable $handler
     */
    protected function addRoute($method, $path, $handler)
    {
        $this->routes[$method][] = [$path => $handler];
    }

    /**
     * Parse named parameters inside route path
     * @param string $requestUri
     * @param string $resource
     * @return array
     */
    protected function parseRegexRoute($requestUri, $resource)
    {
        $route = preg_replace_callback(self::REGVAL, function ($matches) {
            $patterns = $this->patterns;
            $matches[0] = str_replace(['{', '}'], '', $matches[0]);

            if (in_array($matches[0], array_keys($patterns))) {
                return $patterns[$matches[0]];
            }
        }, $resource);


        $regUri = explode('/', $resource);

        $args = array_diff(
            array_replace(
                $regUri,
                explode('/', $requestUri)
            ),
            $regUri
        );

        return [array_values($args), $resource, $route];
    }
}
