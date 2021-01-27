<?php

namespace App\core\event;

use App\core\event\listeners\RouterListener;

class ListenerService
{

    public $listeners = [
        ['class' => 'App\core\event\listeners\RouterListener', 'callbackMethod' => 'onRequest', 'eventName' => EventNames::REQUEST],
        ['class' => 'App\core\event\listeners\FakeListener', 'callbackMethod' => 'onResponse', 'eventName' =>  EventNames::RESPONSE]
        ];


    protected $instantiatedClasses;

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->instanciateClasses();
    }

    public function instanciateClasses()
    {
        foreach ($this->listeners as $key => $listener) {
            $instantiatedClass = new $listener['class'];
            $this->instantiatedClasses[$key]['class'] = $instantiatedClass;
            $this->instantiatedClasses[$key]['callbackMethod'] = $listener['callbackMethod'];
            $this->instantiatedClasses[$key]['eventName'] = $listener['eventName'];
        }

        dump($this->instantiatedClasses);
    }

    public function setListeners()
    {
        foreach ($this->instantiatedClasses as $listener) {
            $class = $listener['class'];
            $callback = $listener['callbackMethod'];
            $eventName = $listener['eventName'];
            $class->setListener($eventName, $callback, $this->dispatcher);
        }
    }

}
