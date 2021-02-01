<?php


namespace App\core\controller;

use App\Core\Http\Request;

class ControllerResolver
{
    public function getController(Request $request)
    {
        if (!$controllerPath = $request->getAttribute('controller')) {
            return false;
        }

        $instanciatedController = $this->createController($controllerPath);

        return $instanciatedController;
    }

    protected function createController(string $controllerPath)
    {
        [$class, $method] = explode('::', $controllerPath, 2);
        $class = new $class();

        return $controller = [$class, $method];
    }
}
