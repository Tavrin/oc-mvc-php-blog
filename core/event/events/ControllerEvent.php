<?php


namespace App\core\event\events;

use App\core\Kernel;

class ControllerEvent
{
    private $kernel;
    private $controller;

    public function __construct(Kernel $kernel, $controller)
    {
        $this->kernel = $kernel;
        $this->controller = $controller;
    }

    public function getController()
    {
        return $this->controller;
    }
}