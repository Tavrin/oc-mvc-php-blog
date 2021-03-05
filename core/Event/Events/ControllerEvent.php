<?php


namespace Core\Event\Events;

use Core\Kernel;

class ControllerEvent
{
    private Kernel $kernel;
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
