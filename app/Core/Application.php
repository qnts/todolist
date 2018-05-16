<?php

namespace App\Core;

final class Application
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
     * @return void
     */
    public function __construct($path = '')
    {
        // register base path for application
        $this->basePath = $path;

        require_once($this->basePath . '/app/helpers.php');

        // load configs
        $this->loadConfigs();

        // capture current request
        $this->request = new Http\Request();

        // connect db
        $this->db = new Database(Config::get('database'));

        // register singleton objects
        $container = Utilities\Container::getInstance();
        $container->register([
            'db' => $this->db,
            'request' => $this->request,
            'router' => Router::class,
            'config' => Config::getInstance(),
        ]);

        self::$instance = $this;
    }

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

    public function request()
    {
        return $this->request;
    }

    public function getBasePath()
    {
        return $this->basePath;
    }

    public function run()
    {
        // register routes
        require_once($this->basePath . '/config/routes.php');

        // Routing and dispatching
        $routeResult = $router->parse();
        if (!$routeResult) {
            $response = new Http\Response('<h1>404 Not Found</h1>', [], 404);
            $response->output();
        } else {
            // call direct action
            if (isset($routeResult['handler'])) {
                $response = call_user_func_array($routeResult['handler'], $routeResult['args']);
            } else {
                // call controller action
                $response = Http\Controller::invoke($routeResult);
            }
            // output response
            if ($response instanceof Http\Response) {
                $response->output();
            } elseif ($response instanceof View) {
                $response->render()->output();
            } elseif (is_string($response)) {
                (new Http\Response($response))->output();
            }
        }

        // end cycle
        $this->db->close();
    }
}
