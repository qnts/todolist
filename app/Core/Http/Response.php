<?php

namespace App\Core\Http;

class Response
{
    /**
     * Response body
     */
    protected $body = '';
    /**
     * Response headers
     */
    protected $headers = [];
    /**
     * Response status code
     */
    protected $status = 200;

    public function __construct($body = '', $headers = [], $status = 200)
    {
        $this->body = $body;
        // set a default header
        $this->setHeader('Content-Type', 'text/html; charset=utf-8');
        $this->headers = array_merge($this->headers, $headers);
        $this->setStatus($status);
    }

    public function setHeader($name, $value = null)
    {
        $this->headers[$name] = $value;
    }

    /**
     * Get the value of Response body
     *
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get the value of Response headers
     *
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get the value of Response status code
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Output response
     */
    public function output()
    {
        http_response_code($this->status);
        foreach ($this->headers as $key => $value) {
            if (!$value) {
                header($key);
            } else {
                header($key . ':' . $value);
            }
        }
        echo $this->body;
    }

    public function redirect($url)
    {
        $this->setHeader('Location', $url);
    }

    /**
     * Set the value of Response status code
     *
     * @param mixed status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;
        http_response_code($status);
        return $this;
    }
}
