<?php

namespace App\Core;


use App\Core\Http\ViewResponse;
use App\Core\Routing\Route;
use App\Core\Routing\Router;

class Application
{
    /**
     * Application base path
     */
    protected $basePath;

    /**
     * DB object
     */
    protected $db;

    /**
     * Request object
     */
    protected $request;

    protected static $instance;

    /**
     * Create an instance of application
     * @param string $path path to root dir
     */
    public function __construct($path = '')
    {
        // init session
        \session_start();
        // register base path for application
        $this->basePath = $path;

        require_once($this->basePath . '/app/helpers.php');

        // load configs
        $this->loadConfigs();

        // capture current request
        $this->request = new Http\Request();

        // connect db
        $this->db = new Database();

        // register singleton objects
        $container = Utilities\Container::getInstance();
        $container->register([
            'db' => $this->db,
            'request' => $this->request,
            'router' => Router::class,
            'config' => Config::getInstance(),
            'session' => Session::class,
        ]);

        self::$instance = $this;
    }

    /**
     * Get the singleton Application object
     * @return Application
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * Load config from file
     * @return void
     */
    protected function loadConfigs()
    {
        $configs = include rtrim($this->basePath, '/') . '/config/app.php';
        Config::getInstance($configs);
    }

    /**
     * Get database object
     * @return Database
     */
    public function getDatabase()
    {
        return $this->db;
    }

    /**
     * Get the singleton request object
     * @return Http\Request
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * Get base path for this app
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Run the application
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        // register routes
        require_once($this->basePath . '/config/routes.php');

        // Routing and dispatching
        /**
         * @var Router $router
         * @var Route $route
         */
        $route = $router->parse();
        if (!$route) {
            response_404()->output();
        } else {
            // call direct function
            if (is_callable($route->getHandler())) {
                $response = call_user_func_array($route->getHandler(), $route->getArgs());
            } else {
                // call controller action
                $response = Http\Controller::invoke($route);
            }
            // output response
            if ($response instanceof Http\Response) {
                $response->output();
            } elseif ($response instanceof View) {
                (new ViewResponse($response))->output();
            } elseif (is_string($response)) {
                (new Http\Response($response))->output();
            } elseif (is_array($response)) {
                json($response)->output();
            } else {
                // 501 Not Implemented
                http_response_code(501);
                // or throw error
                // never mind :)
            }
        }
    }

    /**
     * Terminate the process
     */
    public function terminate()
    {
        // end cycle
        $this->db->close();
        die;
    }
}
