<?php

use App\Core\Application;
use App\Core\Router;
use App\Core\Http\Response;
use App\Core\View;
use App\Core\Utilities\Container;

function resolve($name)
{
    return Container::getInstance()->resolve($name);
}

function app()
{
    return Application::getInstance();
}

function request()
{
    return resolve('request');
}

function redirect($url = '/', $params = [])
{
    $fullUrl = url($url, $params);
    $response = new Response();
    $response->redirect($fullUrl);
    return $response;
}

function url($url = '/', $params = [])
{
    // is an application url
    if (substr($url, 0, 1) === '/') {
        $protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $path = dirname($_SERVER['PHP_SELF']);
        // inject params into $url
        foreach ($params as $name => $value) {
            $url = str_replace("{:$name}", $value, $url);
        }
        // clean unassigned param to avoid exploit
        $url = preg_replace(Router::REGVAL, '', $url);
        // build the final url
        $fullUrl = sprintf('%1$s://%2$s%3$s%4$s', $protocol, $host, $path, rtrim($url, '/'));
        return $fullUrl;
    } else {
        // direct to the url
        return $url;
    }
}

function view($path, $data = [])
{
    return new View($path, $data);
}

function config($key)
{
    return resolve('config')->get($key);
}
