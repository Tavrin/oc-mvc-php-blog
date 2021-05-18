<?php


namespace Core\controller;

use Core\database\EntityManager;
use Core\http\Request;

class ControllerResolver
{
    /**
     * @param Request $request
     * @param EntityManager|null $entityManager
     * @return array|false
     */
    public function getController(Request $request, ?EntityManager $entityManager = null)
    {
        if (!$controllerPath = $request->getAttribute('controller')) {
            return false;
        }

        return self::createController($controllerPath, $request, $entityManager);

    }

    /**
     * @param string $controllerPath
     * @param Request|null $request
     * @param EntityManager|null $entityManager
     * @param array $options
     * @return array
     */
    public static function createController(string $controllerPath, Request $request = null,EntityManager $entityManager = null):array
    {
        [$class, $method] = explode('::', $controllerPath, 2);

        $class = new $class($request, $entityManager);


        return [$class, $method];
    }
}
