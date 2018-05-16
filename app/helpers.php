<?php

use App\Core\Application;
use App\Core\Router;
use App\Core\Http\Response;
use App\Core\View;
use App\Core\Utilities\Container;

/**
 * Shortcut for Container resolve
 * @param $name
 * @return mixed
 */
function resolve($name)
{
    return Container::getInstance()->resolve($name);
}

/**
 * Get the singleton app
 * @return Application
 */
function app()
{
    return Application::getInstance();
}

/**
 * Get the singleton request
 * @return \App\Core\Http\Request
 */
function request()
{
    return resolve('request');
}

/**
 * Return a response for redirection
 * @param string $url
 * @param array $params
 * @return Response
 */
function redirect($url = '/', $params = [])
{
    $fullUrl = url($url, $params);
    $response = new Response();
    $response->redirect($fullUrl);
    return $response;
}

/**
 * Construct url
 * @param string $url
 * @param array $params
 * @return string
 */
function url($url = '/', $params = [])
{
    // is an application url
    if (substr($url, 0, 1) === '/') {
        $protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $paths = explode(basename($_SERVER['SCRIPT_NAME']), dirname($_SERVER['PHP_SELF']));
        $path = trim($paths[0], '/\\');
        // inject params into $url
        foreach ($params as $name => $value) {
            $url = str_replace("{:$name}", $value, $url);
        }
        // clean unassigned param to avoid exploit
        $url = preg_replace(Router::REGVAL, '', $url);
        // build the final url
        if (config('short_url')) {
            $fullUrl = '/' . trim(sprintf('/%1$s%2$s', $path, $url), '/\\');
        } else {
            $fullUrl = sprintf('%1$s://%2$s%3$s%4$s', $protocol, $host, $path, rtrim($url, '/\\'));
        }
        return $fullUrl;
    } else {
        // direct to the url
        return $url;
    }
}

/**
 * Shortcut for instantiate a View
 * @param $path
 * @param array $data
 * @return View
 */
function view($path, $data = [])
{
    return new View($path, $data);
}

/**
 * Get the singleton session
 * @return mixed
 */
function session()
{
    return resolve('session');
}

/**
 * Retrieve a config value
 * @param $key
 * @return mixed
 */
function config($key)
{
    return resolve('config')->get($key);
}

/**
 * Return a json response
 * @param object|array $object
 * @return Response
 */
function json_response($object)
{
    return new Response(json_encode($object), ['Content-type', 'application/json']);
}

/**
 * Return a 404 response
 * @return Response
 */
function response_404()
{
    return new Response('<h1>404 not found</h1>', [], 404);
}
