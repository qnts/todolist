<?php

namespace App\Core\Http;

class JsonResponse extends Response
{
    /**
     * @var mixed underlying json-able object
     */
    protected $data;

    /**
     * JsonResponse constructor.
     * @param $object
     * @param array $headers
     */
    public function __construct($object, $headers = [])
    {
        $this->data = $object;
        parent::__construct(null, $headers, 200);
        $this->setHeader('Content-type', 'application/json');
    }

    /**
     * Get the request's body
     * @return mixed
     */
    public function getBody()
    {
        return $this->data;
    }

    /**
     * Output the response
     */
    public function output()
    {
        $this->body = json_encode($this->data);
        parent::output();
    }
}