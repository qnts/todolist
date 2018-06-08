<?php
/**
 * Created by PhpStorm.
 * User: vnetd
 * Date: 6/8/2018
 * Time: 23:21
 */

namespace App\Core\Utilities;


class FunctionInjector
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * FunctionInjector constructor.
     */
    public function __construct()
    {
        $this->container = Container::getInstance();
    }

    /**
     * @param mixed $function
     * @param array $args
     * @return mixed
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function run($function, $args = [])
    {
        // is class->method
        if (is_array($function)) {
            $reflection = new \ReflectionMethod($function[0], $function[1]);
        } elseif (is_string($function)) {
            // is class::method or a function name
            $reflection = new \ReflectionMethod($function);
        } else {
            // is Closure
            $reflection = new \ReflectionFunction($function);
        }
        $arguments = [];

        foreach ($reflection->getParameters() as $parameter) {
            $parameterClass = $parameter->getClass();
            $parameterName = $parameter->getName();

            if (isset($args[$parameterName])) {
                $arguments[] = $args[$parameterName];
                continue;
            }

            if ($parameterClass == null) {
                if ($parameter->isDefaultValueAvailable()) {
                    $arguments[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception(sprintf('Cannot resolve %s', $parameterName));
                }
            } else {
                // try resolve
                $instance = $this->container->resolve($parameterClass->getName());
                if (!is_null($instance)) {
                    $arguments[] = $instance;
                } else {
                    throw new \Exception(sprintf('Cannot resolve %s', $parameterClass->getName()));
                }
            }

        }

        return call_user_func_array($function, $arguments);
    }
}