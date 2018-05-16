<?php

namespace App\Core\Utilities;

class Container
{
    protected $instances = [];

    protected static $instance;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }

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
        }
    }

    public function resolve($name)
    {
        return $this->instances[$name];
    }
}
