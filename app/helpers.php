<?php

use App\Core\Application;
use App\Core\Router;
use App\Core\Http\Response;
use App\Core\Http\ViewResponse;
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
 * @return Response
 */
function redirect($url = '/')
{
    $fullUrl = url($url);
    $response = new Response();
    $response->redirect($fullUrl);
    return $response;
}

/**
 * Return a response for redirection, using route name and passing args instead
 * @param string $routeName
 * @param array $args
 * @return Response
 */
function to_route($routeName, $args = [])
{
    $url = route($routeName, $args);
    return redirect($url);
}

/**
 * Shortcut for Router::url
 * @param string $url
 * @param null|array $args
 * @return string
 */
function url($url = '/', $args = null)
{
    return resolve('router')->url($url, $args);
}

/**
 * Shortcut for Router::route
 * @param string $url
 * @param array $args
 * @return string
 */
function route($url = '/', $args = [])
{
    return resolve('router')->route($url, $args);
}

/**
 * Shortcut for instantiate a View
 * @param $path
 * @param array $data
 * @return ViewResponse
 * @throws Exception
 */
function view($path, $data = [])
{
    return new ViewResponse($path, $data);
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
function json($object)
{
    return new \App\Core\Http\JsonResponse($object);
}

/**
 * Return a 404 response
 * @return Response
 */
function response_404()
{
    return new Response('<h1>404 not found</h1>', [], 404);
}

/**
 * Debug variables
 */
function dump()
{
    foreach (func_get_args() as $var) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}

/**
 * Debug variables and exit
 */
function dd()
{
    call_user_func_array('dump', func_get_args());
    die;
}