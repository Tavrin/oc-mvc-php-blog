<?php


namespace App\core\controller;

use App\core\database\EntityManager;
use App\Core\Http\Request;

class ControllerResolver
{
    /**
     * @var EntityManager
     */
    public $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getController(Request $request)
    {
        if (!$controllerPath = $request->getAttribute('controller')) {
            return false;
        }

        $instanciatedController = $this->createController($controllerPath);

        return $instanciatedController;
    }

    public function createController(string $controllerPath)
    {
        $entityManager = $this->entityManager;
        [$class, $method] = explode('::', $controllerPath, 2);
        $class = new $class($entityManager);

        return $controller = [$class, $method];
    }
}
