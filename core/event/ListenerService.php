<?php

namespace App\core\event;

use App\core\event\listeners\RouterListener;

class ListenerService
{

    public $listeners = [
        ['class' => 'App\core\event\listeners\RouterListener', 'callbackMethod' => 'onRequest', 'eventName' => EventNames::REQUEST],
        ['class' => 'App\core\event\listeners\FakeListener', 'callbackMethod' => 'onResponse', 'eventName' =>  EventNames::RESPONSE]
        ];

    protected $instanciatedClasses;

    protected $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->instanciateClasses();
    }

    public function instanciateClasses()
    {
        foreach ($this->listeners as $key => $listener) {
            $instanciatedClass = new $listener['class'];
            $this->instanciatedClasses[$key]['class'] = $instanciatedClass;
            $this->instanciatedClasses[$key]['callbackMethod'] = $listener['callbackMethod'];
            $this->instanciatedClasses[$key]['eventName'] = $listener['eventName'];
        }

        dump($this->instanciatedClasses);
    }

    public function setListeners()
    {
        foreach ($this->instanciatedClasses as $listener) {
            $class = $listener['class'];
            $callback = $listener['callbackMethod'];
            $eventName = $listener['eventName'];
            $class->setListener($eventName, $callback, $this->dispatcher);
        }
    }

}
