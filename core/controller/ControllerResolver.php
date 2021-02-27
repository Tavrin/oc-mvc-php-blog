<?php


namespace App\core\controller;

use App\core\database\EntityManager;
use App\Core\Http\Request;

class ControllerResolver
{
    /**
     * @param Request $request
     * @param EntityManager|null $entityManager
     * @return array|false
     */
    public function getController(Request $request, EntityManager $entityManager = null)
    {
        if (!$controllerPath = $request->getAttribute('controller')) {
            return false;
        }

        return self::createController($controllerPath, $entityManager);

    }

    /**
     * @param string $controllerPath
     * @param EntityManager|null $entityManager
     * @return array
     */
    public static function createController(string $controllerPath, EntityManager $entityManager = null):array
    {
        [$class, $method] = explode('::', $controllerPath, 2);
        $class = new $class($entityManager);

        return $controller = [$class, $method];
    }
}
