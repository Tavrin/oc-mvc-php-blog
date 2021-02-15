<?php


namespace App\core\controller;

use App\core\database\EntityManager;
use App\Core\Http\Request;

class ControllerResolver
{
    /**
     * @var EntityManager
     */
    public $entityManager = null;

    public function __construct()
    {
    }

    public function getController(Request $request, EntityManager $entityManager)
    {
        if (!$controllerPath = $request->getAttribute('controller')) {
            return false;
        }

        $instanciatedController = self::createController($controllerPath, $entityManager);

        return $instanciatedController;
    }

    /**
     * @param string $controllerPath
     * @param EntityManager $entityManager
     * @return array
     */
    public static function createController(string $controllerPath, EntityManager $entityManager = null):array
    {
        [$class, $method] = explode('::', $controllerPath, 2);
        $class = new $class($entityManager);

        return $controller = [$class, $method];
    }
}
