<?php

namespace App\Core\Http;

class ViewResponse extends Response
{
    /**
     * @var \App\Core\View underlying View object
     */
    protected $view;

    /**
     * ViewResponse constructor.
     * @param $view
     * @param array $data
     * @throws \Exception
     */
    public function __construct($view, $data = [])
    {
        if ($view instanceof \App\Core\View) {
            $this->view = $view;
        } else {
            $this->view = new \App\Core\View($view, $data);
        }
        parent::__construct(null, [], 200);
        $this->setHeader('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * Output this response
     */
    public function output()
    {
        $this->body = $this->view->render();
        parent::output();
    }

    /**
     * Get the underlying view object
     * @return \App\Core\View
     */
    public function getView()
    {
        return $this->view;
    }
}