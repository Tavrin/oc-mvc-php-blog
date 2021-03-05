<?php

namespace Core\Event;

class ListenerService
{

    public array $listeners = [
        ['class' => 'Core\Event\listeners\RouterListener', 'callbackMethod' => 'onRequest', 'eventName' => EventNames::REQUEST],
        ['class' => 'Core\Event\listeners\FakeListener', 'callbackMethod' => 'onResponse', 'eventName' =>  EventNames::RESPONSE]
        ];


    protected $instantiatedClasses;

    /**
     * @var Dispatcher
     */
    protected Dispatcher $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->instantiateClasses();
    }

    public function instantiateClasses()
    {
        foreach ($this->listeners as $key => $listener) {
            $instantiatedClass = new $listener['class'];
            $this->instantiatedClasses[$key]['class'] = $instantiatedClass;
            $this->instantiatedClasses[$key]['callbackMethod'] = $listener['callbackMethod'];
            $this->instantiatedClasses[$key]['eventName'] = $listener['eventName'];
        }
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
