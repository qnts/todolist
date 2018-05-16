<?php

namespace App\Core\Http;

use App\Core\Utilities\DotNotation;

class Request
{
    /**
     * $_POST wrapper
     */
    public $data;
    /**
     * $_FILES wrapper
     */
    public $files;
    /**
     * QUERY_STRING wrapper
     */
    public $query;
    /**
     * Request method
     */
    public $method;

    /**
     * Constructor. A request object should be singleton.
     */
    public function __construct()
    {
        parse_str(isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '', $query);
        $this->query = new DotNotation($query);
        $this->files = $_FILES;
        $this->data = new DotNotation($_POST);
        $this->method = 'GET';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($this->input('_method')) {
                case 'PUT':
                    $this->method = 'PUT';
                    break;
                case 'DELETE':
                    $this->method = 'DELETE';
                    break;
                default:
                    $this->method = 'POST';
            }
        }
    }

    /**
     * Retrieve a query string key
     * @param string $key
     * @param mixed $default
     * @return mixed|null|string
     */
    public function query($key, $default = '')
    {
        $value = $this->query->get($key);
        return is_null($value) ? $default : $value;
    }

    /**
     * Retrieve a $_POST value
     * @param string $key
     * @param mixed $default
     * @return array|mixed|null|string
     */
    public function input($key = '', $default = '')
    {
        if (empty($key)) {
            return $this->data->toArray();
        }
        $value = $this->data->get($key);
        return is_null($value) ? $default : $value;
    }
}
