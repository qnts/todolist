<?php

namespace App\Core;

class Config
{
    /**
     * Configs dot notation object
     */
    protected $dotNotation;
    /**
     * @var the singleton config instance
     */
    protected static $instance;

    /**
     * Config constructor.
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        $this->dotNotation = new Utilities\DotNotation($configs);
    }

    /**
     * Retrieve singleton object
     * @param null|array $configs
     * @return Config
     */
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
