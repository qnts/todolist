<?php

namespace App\Core\Utilities;

class Container
{
    /**
     * @var array List of registered singleton object
     */
    protected $instances = [];

    protected $classNames = [];

    /**
     * @var Container
     */
    protected static $instance;

    /**
     * Get instance of this container
     * @return Container
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * Register singleton object
     * @param array|string $name
     * @param mixed $instance
     */
    public function register($name, $instance = null)
    {
        if (is_array($name)) {
            foreach ($name as $n => $instance) {
                $this->register($n, $instance);
            }
        } else {
            if (is_string($instance)) {
                $instance = new $instance();
            }
            $this->instances[$name] = $instance;
            $this->classNames[get_class($instance)] = $instance;
        }
    }

    /**
     * Retrieve the registered object
     * @param string $nameOrClassName name of object or full class name
     * @return mixed
     */
    public function resolve($nameOrClassName)
    {
        if (strpos($nameOrClassName, '\\') !== false) {
            return isset($this->classNames[$nameOrClassName]) ? $this->classNames[$nameOrClassName] : null;
        }
        return isset($this->instances[$nameOrClassName]) ? $this->instances[$nameOrClassName] : null;
    }
}
