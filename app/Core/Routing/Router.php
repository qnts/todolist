<?php

namespace App\Core\Routing;

class Router
{
    /**
     * @var array list of Routes
     */
    public $routes = [
        'GET'    => [],
        'POST'   => [],
        'ANY'    => [],
        'PUT'    => [],
        'DELETE' => [],
    ];

    /**
     * @var array list of named Routes
     */
    protected $namedRoutes = [];

    /**
     * @var Route
     */
    private $lastRoute;

    /**
     * @var Route
     */
    protected $currentRoute;

    /**
     * Match an url with any methods
     * @param string $path
     * @param mixed $handler
     * @return self
     */
    public function any($path, $handler)
    {
        return $this->addRoute('ANY', $path, $handler);
    }

    /**
     * Register a get route
     * @param string $path
     * @param mixed $handler
     * @return self
     */
    public function get($path, $handler)
    {
        return $this->addRoute('GET', $path, $handler);
    }

    /**
     * Register a post route
     * @param string $path
     * @param mixed $handler
     * @return self
     */
    public function post($path, $handler)
    {
        return $this->addRoute('POST', $path, $handler);
    }

    /**
     * Register a put route
     * @param string $path
     * @param mixed $handler
     * @return self
     */
    public function put($path, $handler)
    {
        return $this->addRoute('PUT', $path, $handler);
    }

    /**
     * Register a delete route
     * @param string $path
     * @param mixed $handler
     * @return self
     */
    public function delete($path, $handler)
    {
        return $this->addRoute('DELETE', $path, $handler);
    }

    /**
     * Parse request and get the active route
     * @return mixed return Route on success or null if no route matches the request
     */
    public function parse()
    {
        $method = request()->method;
        $requestUri = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';

        /**
         * @var Route $route
         */
        foreach ($this->routes[$method] as $route) {
            if ($route->match($requestUri)) {
                $this->currentRoute = $route;
                return $route;
            }
        }

        return null;
    }

    /**
     * Add a route to routes list
     * @param string $method
     * @param string $path
     * @param callable $handler
     * @return self
     */
    protected function addRoute($method, $path, $handler)
    {
        $this->lastRoute = new Route($path, $handler);
        $this->routes[$method][] = $this->lastRoute;
        return $this;
    }

    /**
     * Name the last registered route
     * @param $name
     * @return $this
     */
    public function name($name)
    {
        $this->namedRoutes[$name] = $this->lastRoute;
        return $this;
    }

    /**
     * Generate url
     * @param $url
     * @param null|array $args
     * @return string
     * @throws \Exception
     */
    public function url($url, $args = null)
    {
        if (substr($url, 0, 1) === '/') {
            // is application url
            if (is_null($args)) {
                return self::getSchema() . trim($url, '/');
            } else {
                $route = new Route($url, null);
                return $route->toUrl($args);
            }
        } else {
            // is external url
            return $url;
        }
    }

    /**
     * Generate url for a named route
     * @param $name
     * @param array $args
     * @return string
     * @throws UnknownRouteNameException if no named routes found
     */
    public function route($name, $args = [])
    {
        if (isset($this->namedRoutes[$name])) {
            /**
             * @throws MissingArgumentException
             */
            return $this->namedRoutes[$name]->toUrl($args);
        }
        throw new UnknownRouteNameException($name);
    }

    public function getCurrentRoute()
    {
        return $this->currentRoute;
    }

    public static function getSchema()
    {
        if (config('short_url')) {
            return '/';
        }
        $protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $paths = explode(basename($_SERVER['SCRIPT_NAME']), dirname($_SERVER['PHP_SELF']));
        $path = trim($paths[0], '/\\');
        return sprintf('%1$s://%2$s%3$s%4$s/', $protocol, $host, $path);
    }

}
