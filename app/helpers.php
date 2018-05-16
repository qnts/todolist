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
        $paths = explode(basename($_SERVER['SCRIPT_NAME']), dirname($_SERVER['PHP_SELF']));
        $path = trim($paths[0], '/');
        // inject params into $url
        foreach ($params as $name => $value) {
            $url = str_replace("{:$name}", $value, $url);
        }
        // clean unassigned param to avoid exploit
        $url = preg_replace(Router::REGVAL, '', $url);
        // build the final url
        if (config('short_url')) {
            $fullUrl = '/' . trim(sprintf('/%1$s%2$s', $path, $url), '/');
        } else {
            $fullUrl = sprintf('%1$s://%2$s%3$s%4$s', $protocol, $host, $path, rtrim($url, '/'));
        }
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

function session()
{
    return resolve('session');
}

function config($key)
{
    return resolve('config')->get($key);
}

function json_response($object)
{
    return new Response(json_encode($object), ['Content-type', 'application/json']);
}

function response_404()
{
    return new Response('<h1>404 not found</h1>', [], 404);
}
