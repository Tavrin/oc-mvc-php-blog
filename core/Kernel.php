<?php

namespace App\core;

use App\Core\Http\Request;
use App\Core\Event\Dispatcher;
use App\core\event\EventNames;
use App\core\event\ListenerService;
use App\core\event\events\RequestEvent;

/**
 * Class Kernel
 * @package App\core
 */
class Kernel
{
    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var ListenerService
     */
    protected $listenerService;

    public function __construct()
    {
        $this->setServices();

    }

    public function setServices()
    {
        if (null === $this->dispatcher) {
            $this->setDispatcher();
        }

        $dispatcher = $this->dispatcher;
        $this->listenerService = new ListenerService($dispatcher);
        $this->listenerService->setListeners();
    }

    public function setDispatcher()
    {
        $this->dispatcher = new Dispatcher();
    }

    /**
     * @param Request $request
     */
    public function route(Request $request)
    {
        $event = new RequestEvent($this, $request);
        $this->dispatcher->dispatch($event,EventNames::REQUEST);
    }

}
