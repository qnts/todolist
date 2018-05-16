<?php

namespace App\Core;

class Config
{
    /**
     * Configs array
     */
    protected $dotNotation;

    protected static $instance;

    public function __construct($configs)
    {
        $this->dotNotation = new Utilities\DotNotation($configs);
    }

    public static function getInstance($configs = null)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($configs);
        }
        return self::$instance;
    }

    /**
     * Get a particular value back from the config array
     * @param string $index The index to fetch in dot notation
     * @return mixed
     */
    public static function get($index)
    {
        return self::getInstance()->dotNotation->get($index);
    }
}
